use axum::{
    extract::{Query, State},
    http::{header, HeaderMap, StatusCode},
    response::{IntoResponse, Response},
};
use serde::Deserialize;
use std::sync::Arc;

use crate::badge::{BadgeRenderer, BadgeStyle};
use crate::storage::CounterStorage;
use crate::username::Username;

#[derive(Debug, Deserialize)]
pub struct BadgeQuery {
    pub username: String,

    #[serde(default = "default_color")]
    pub color: String,

    #[serde(default)]
    pub style: Option<String>,

    #[serde(default = "default_label")]
    pub label: String,

    #[serde(default)]
    pub base: Option<u64>,

    #[serde(default)]
    pub abbreviated: bool,
}

fn default_color() -> String {
    "blue".to_string()
}

fn default_label() -> String {
    "Profile views".to_string()
}

pub struct SvgResponse(pub String);

impl IntoResponse for SvgResponse {
    fn into_response(self) -> Response {
        (
            StatusCode::OK,
            [
                (header::CONTENT_TYPE, "image/svg+xml"),
                (header::CACHE_CONTROL, "max-age=0, no-cache, no-store, must-revalidate"),
                (header::PRAGMA, "no-cache"),
                (header::EXPIRES, "0"),
            ],
            self.0,
        )
            .into_response()
    }
}

fn is_github_camo(headers: &HeaderMap) -> bool {
    headers
        .get(header::USER_AGENT)
        .and_then(|v| v.to_str().ok())
        .map(|ua| ua.starts_with("github-camo"))
        .unwrap_or(false)
}

pub async fn badge_handler<S: CounterStorage>(
    State(storage): State<Arc<S>>,
    headers: HeaderMap,
    Query(query): Query<BadgeQuery>,
) -> Response {
    // Validate username
    let username = match Username::new(&query.username) {
        Ok(u) => u,
        Err(e) => {
            let svg = BadgeRenderer::render_error(&query.label, &e.to_string());
            return SvgResponse(svg).into_response();
        }
    };

    // Parse style
    let style = query
        .style
        .as_deref()
        .and_then(BadgeStyle::from_str)
        .unwrap_or_default();

    // Only increment if request is from GitHub Camo proxy
    let count = if is_github_camo(&headers) {
        match storage.increment(&username).await {
            Ok(c) => c,
            Err(e) => {
                tracing::error!("Storage error: {}", e);
                let svg = BadgeRenderer::render_error(&query.label, "Storage error");
                return SvgResponse(svg).into_response();
            }
        }
    } else {
        match storage.get_count(&username).await {
            Ok(c) => c,
            Err(e) => {
                tracing::error!("Storage error: {}", e);
                let svg = BadgeRenderer::render_error(&query.label, "Storage error");
                return SvgResponse(svg).into_response();
            }
        }
    };

    // Add base count if provided
    let total_count = count + query.base.unwrap_or(0);

    // Render badge
    let svg = BadgeRenderer::render(
        &query.label,
        total_count,
        &query.color,
        style,
        query.abbreviated,
    );

    SvgResponse(svg).into_response()
}

pub async fn health_handler() -> &'static str {
    "OK"
}
