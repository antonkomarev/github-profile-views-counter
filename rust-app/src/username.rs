use crate::error::{AppError, Result};

#[derive(Debug, Clone)]
pub struct Username(String);

impl Username {
    pub fn new(value: &str) -> Result<Self> {
        let trimmed = value.trim();

        if trimmed.is_empty() {
            return Err(AppError::InvalidUsername("Username cannot be empty".into()));
        }

        if trimmed.len() > 39 {
            return Err(AppError::InvalidUsername(
                "Username must be 39 characters or less".into(),
            ));
        }

        if !Self::is_valid_username(trimmed) {
            return Err(AppError::InvalidUsername(
                "Username may only contain alphanumeric characters or single hyphens, \
                 and cannot begin or end with a hyphen"
                    .into(),
            ));
        }

        Ok(Self(trimmed.to_lowercase()))
    }

    fn is_valid_username(s: &str) -> bool {
        let chars: Vec<char> = s.chars().collect();

        // Must start with alphanumeric
        if !chars.first().is_some_and(|c| c.is_ascii_alphanumeric()) {
            return false;
        }

        // Must end with alphanumeric
        if !chars.last().is_some_and(|c| c.is_ascii_alphanumeric()) {
            return false;
        }

        // Check all characters and no consecutive hyphens
        let mut prev_hyphen = false;
        for c in &chars {
            if *c == '-' {
                if prev_hyphen {
                    return false; // Consecutive hyphens not allowed
                }
                prev_hyphen = true;
            } else if c.is_ascii_alphanumeric() {
                prev_hyphen = false;
            } else {
                return false; // Invalid character
            }
        }

        true
    }

    pub fn as_str(&self) -> &str {
        &self.0
    }
}

impl std::fmt::Display for Username {
    fn fmt(&self, f: &mut std::fmt::Formatter<'_>) -> std::fmt::Result {
        write!(f, "{}", self.0)
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_valid_usernames() {
        assert!(Username::new("antonkomarev").is_ok());
        assert!(Username::new("user-name").is_ok());
        assert!(Username::new("user123").is_ok());
        assert!(Username::new("a").is_ok());
    }

    #[test]
    fn test_invalid_usernames() {
        assert!(Username::new("").is_err());
        assert!(Username::new("-username").is_err());
        assert!(Username::new("username-").is_err());
        assert!(Username::new("user--name").is_err());
        assert!(Username::new(&"a".repeat(40)).is_err());
    }

    #[test]
    fn test_lowercase_normalization() {
        let username = Username::new("AntonKomarev").unwrap();
        assert_eq!(username.as_str(), "antonkomarev");
    }
}
