use crate::error::{AppError, Result};
use crate::username::Username;
use sqlx::PgPool;

pub struct PostgresStorage {
    pool: PgPool,
}

impl PostgresStorage {
    pub fn new(pool: PgPool) -> Self {
        Self { pool }
    }

    pub async fn from_url(database_url: &str) -> Result<Self> {
        let pool = PgPool::connect(database_url)
            .await
            .map_err(|e| AppError::Storage(format!("Failed to connect to database: {}", e)))?;

        Ok(Self { pool })
    }

    pub async fn run_migrations(&self) -> Result<()> {
        sqlx::query(
            r#"
            CREATE TABLE IF NOT EXISTS github_profile_views (
                id SERIAL PRIMARY KEY,
                username VARCHAR(39) NOT NULL,
                created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
            );
            CREATE INDEX IF NOT EXISTS idx_github_profile_views_username
            ON github_profile_views(username);
            "#,
        )
        .execute(&self.pool)
        .await
        .map_err(|e| AppError::Storage(format!("Failed to run migrations: {}", e)))?;

        Ok(())
    }
}

impl super::CounterStorage for PostgresStorage {
    async fn increment(&self, username: &Username) -> Result<u64> {
        sqlx::query("INSERT INTO github_profile_views (username) VALUES ($1)")
            .bind(username.as_str())
            .execute(&self.pool)
            .await
            .map_err(|e| AppError::Storage(format!("Failed to insert view: {}", e)))?;

        self.get_count(username).await
    }

    async fn get_count(&self, username: &Username) -> Result<u64> {
        let row: (i64,) =
            sqlx::query_as("SELECT COUNT(*) FROM github_profile_views WHERE username = $1")
                .bind(username.as_str())
                .fetch_one(&self.pool)
                .await
                .map_err(|e| AppError::Storage(format!("Failed to get count: {}", e)))?;

        Ok(row.0 as u64)
    }
}
