use crate::error::{AppError, Result};
use crate::username::Username;
use fs2::FileExt;
use std::fs::{self, File, OpenOptions};
use std::io::{Read, Write};
use std::path::PathBuf;

pub struct FileStorage {
    storage_path: PathBuf,
}

impl FileStorage {
    pub fn new(storage_path: PathBuf) -> Result<Self> {
        fs::create_dir_all(&storage_path)?;
        Ok(Self { storage_path })
    }

    fn count_file_path(&self, username: &Username) -> PathBuf {
        self.storage_path
            .join(format!("{}-views-count", username.as_str()))
    }

    fn log_file_path(&self, username: &Username) -> PathBuf {
        self.storage_path
            .join(format!("{}-views", username.as_str()))
    }

    fn read_count(&self, username: &Username) -> Result<u64> {
        let path = self.count_file_path(username);

        if !path.exists() {
            return Ok(0);
        }

        let mut file = File::open(&path)?;
        file.lock_exclusive()
            .map_err(|e| AppError::Storage(format!("Failed to lock file: {}", e)))?;

        let mut contents = String::new();
        file.read_to_string(&mut contents)?;

        file.unlock()
            .map_err(|e| AppError::Storage(format!("Failed to unlock file: {}", e)))?;

        contents
            .trim()
            .parse()
            .map_err(|e| AppError::Storage(format!("Failed to parse count: {}", e)))
    }

    fn write_count(&self, username: &Username, count: u64) -> Result<()> {
        let path = self.count_file_path(username);

        let mut file = OpenOptions::new()
            .write(true)
            .create(true)
            .truncate(true)
            .open(&path)?;

        file.lock_exclusive()
            .map_err(|e| AppError::Storage(format!("Failed to lock file: {}", e)))?;

        write!(file, "{}", count)?;

        file.unlock()
            .map_err(|e| AppError::Storage(format!("Failed to unlock file: {}", e)))?;

        Ok(())
    }

    fn append_log(&self, username: &Username) -> Result<()> {
        let path = self.log_file_path(username);

        let mut file = OpenOptions::new()
            .write(true)
            .create(true)
            .append(true)
            .open(&path)?;

        file.lock_exclusive()
            .map_err(|e| AppError::Storage(format!("Failed to lock file: {}", e)))?;

        let timestamp = std::time::SystemTime::now()
            .duration_since(std::time::UNIX_EPOCH)
            .unwrap()
            .as_secs();

        writeln!(file, "{}", timestamp)?;

        file.unlock()
            .map_err(|e| AppError::Storage(format!("Failed to unlock file: {}", e)))?;

        Ok(())
    }
}

impl super::CounterStorage for FileStorage {
    async fn increment(&self, username: &Username) -> Result<u64> {
        // Use tokio's spawn_blocking for file I/O
        let current = self.read_count(username)?;
        let new_count = current + 1;
        self.write_count(username, new_count)?;
        self.append_log(username)?;
        Ok(new_count)
    }

    async fn get_count(&self, username: &Username) -> Result<u64> {
        self.read_count(username)
    }
}

#[cfg(test)]
mod tests {
    use super::*;
    use crate::storage::CounterStorage;
    use tempfile::tempdir;

    #[tokio::test]
    async fn test_increment_and_get() {
        let dir = tempdir().unwrap();
        let storage = FileStorage::new(dir.path().to_path_buf()).unwrap();
        let username = Username::new("testuser").unwrap();

        assert_eq!(storage.get_count(&username).await.unwrap(), 0);

        let count = storage.increment(&username).await.unwrap();
        assert_eq!(count, 1);

        let count = storage.increment(&username).await.unwrap();
        assert_eq!(count, 2);

        assert_eq!(storage.get_count(&username).await.unwrap(), 2);
    }
}
