mod badge;
mod config;
mod error;
mod handler;
mod storage;
mod username;

use axum::{routing::get, Router};
use std::sync::Arc;
use tracing_subscriber::{layer::SubscriberExt, util::SubscriberInitExt};

use config::{Config, StorageType};
use handler::{badge_handler, health_handler};
use storage::{CounterStorage, FileStorage};

#[tokio::main]
async fn main() -> Result<(), Box<dyn std::error::Error>> {
    // Initialize tracing
    tracing_subscriber::registry()
        .with(
            tracing_subscriber::EnvFilter::try_from_default_env()
                .unwrap_or_else(|_| "info".into()),
        )
        .with(tracing_subscriber::fmt::layer())
        .init();

    // Load configuration
    let config = Config::from_env();
    tracing::info!("Starting server with config: {:?}", config);

    // Create router based on storage type
    let app = match config.storage_type {
        StorageType::File => {
            let storage = FileStorage::new(config.storage_path.clone())?;
            create_router(storage)
        }
        #[cfg(feature = "postgres")]
        StorageType::Postgres => {
            let database_url = config
                .database_url
                .as_ref()
                .expect("DATABASE_URL is required for postgres storage");
            let storage = storage::PostgresStorage::from_url(database_url).await?;
            storage.run_migrations().await?;
            create_router(storage)
        }
    };

    // Start server
    let listener = tokio::net::TcpListener::bind(config.address()).await?;
    tracing::info!("Listening on {}", listener.local_addr()?);

    axum::serve(listener, app).await?;

    Ok(())
}

fn create_router<S: CounterStorage + 'static>(storage: S) -> Router {
    let storage = Arc::new(storage);

    Router::new()
        .route("/", get(badge_handler::<S>))
        .route("/health", get(health_handler))
        .with_state(storage)
}
