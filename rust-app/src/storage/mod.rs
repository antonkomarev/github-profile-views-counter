mod file;
#[cfg(feature = "postgres")]
mod postgres;

pub use file::FileStorage;
#[cfg(feature = "postgres")]
pub use postgres::PostgresStorage;

use crate::error::Result;
use crate::username::Username;
use std::future::Future;

pub trait CounterStorage: Send + Sync {
    fn increment(&self, username: &Username) -> impl Future<Output = Result<u64>> + Send;
    fn get_count(&self, username: &Username) -> impl Future<Output = Result<u64>> + Send;
}
