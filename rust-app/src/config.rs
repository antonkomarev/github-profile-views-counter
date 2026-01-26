use std::env;
use std::path::PathBuf;

#[derive(Debug, Clone)]
pub enum StorageType {
    File,

    #[cfg(feature = "postgres")]
    Postgres,
}

#[derive(Debug, Clone)]
pub struct Config {
    pub host: String,

    pub port: u16,

    pub storage_type: StorageType,

    pub storage_path: PathBuf,

    #[cfg(feature = "postgres")]
    pub database_url: Option<String>,
}

impl Config {
    pub fn from_env() -> Self {
        let _ = dotenvy::dotenv();

        let storage_type = match env::var("REPOSITORY").as_deref() {
            #[cfg(feature = "postgres")]
            Ok("pdo") | Ok("postgres") => StorageType::Postgres,
            _ => StorageType::File,
        };

        Self {
            host: env::var("HOST").unwrap_or_else(|_| "0.0.0.0".to_string()),
            port: env::var("PORT")
                .ok()
                .and_then(|p| p.parse().ok())
                .unwrap_or(8080),
            storage_type,
            storage_path: env::var("STORAGE_PATH")
                .map(PathBuf::from)
                .unwrap_or_else(|_| PathBuf::from("./storage")),
            #[cfg(feature = "postgres")]
            database_url: env::var("DATABASE_URL").ok(),
        }
    }

    pub fn address(&self) -> String {
        format!("{}:{}", self.host, self.port)
    }
}
