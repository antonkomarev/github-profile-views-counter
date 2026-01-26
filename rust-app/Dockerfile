# Build stage
FROM rust:1.83-slim-bookworm AS builder

WORKDIR /app

# Install build dependencies
RUN apt-get update && apt-get install -y \
    pkg-config \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Copy manifests
COPY Cargo.toml Cargo.lock* ./

# Create a dummy main.rs to cache dependencies
RUN mkdir src && \
    echo "fn main() {}" > src/main.rs && \
    cargo build --release && \
    rm -rf src

# Copy source code
COPY src ./src

# Build the application
RUN touch src/main.rs && cargo build --release

# Runtime stage
FROM debian:bookworm-slim

WORKDIR /app

# Install runtime dependencies
RUN apt-get update && apt-get install -y \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Create storage directory
RUN mkdir -p /app/storage

# Copy the binary
COPY --from=builder /app/target/release/github-profile-views-counter /app/

# Set environment variables
ENV HOST=0.0.0.0
ENV PORT=8080
ENV STORAGE_PATH=/app/storage
ENV RUST_LOG=info

# Expose port
EXPOSE 8080

# Run the application
CMD ["./github-profile-views-counter"]
