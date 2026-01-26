use shields::{render_badge_svg, BadgeParams, BadgeStyle as ShieldsBadgeStyle};

const ABBREVIATIONS: [&str; 7] = ["", "K", "M", "B", "T", "Qa", "Qi"];

#[derive(Debug, Clone, Copy, PartialEq, Eq)]
pub enum BadgeStyle {
    Flat,
    FlatSquare,
    Plastic,
    ForTheBadge,
    Pixel,
}

impl BadgeStyle {
    pub fn from_str(s: &str) -> Option<Self> {
        match s.to_lowercase().as_str() {
            "flat" => Some(Self::Flat),
            "flat-square" => Some(Self::FlatSquare),
            "plastic" => Some(Self::Plastic),
            "for-the-badge" => Some(Self::ForTheBadge),
            "pixel" => Some(Self::Pixel),
            _ => None,
        }
    }

    fn to_shields_style(self) -> ShieldsBadgeStyle {
        match self {
            Self::Flat => ShieldsBadgeStyle::Flat,
            Self::FlatSquare => ShieldsBadgeStyle::FlatSquare,
            Self::Plastic => ShieldsBadgeStyle::Plastic,
            Self::ForTheBadge => ShieldsBadgeStyle::ForTheBadge,
            Self::Pixel => ShieldsBadgeStyle::Flat, // Not used for pixel
        }
    }
}

impl Default for BadgeStyle {
    fn default() -> Self {
        Self::Flat
    }
}

pub struct BadgeRenderer;

impl BadgeRenderer {
    pub fn render(
        label: &str,
        count: u64,
        color: &str,
        style: BadgeStyle,
        abbreviated: bool,
    ) -> String {
        if style == BadgeStyle::Pixel {
            return Self::render_pixel();
        }

        let message = if abbreviated {
            Self::format_abbreviated(count)
        } else {
            Self::format_with_commas(count)
        };

        let params = BadgeParams {
            style: style.to_shields_style(),
            label: Some(label),
            message: Some(&message),
            label_color: None,
            message_color: Some(color),
            link: None,
            extra_link: None,
            logo: None,
            logo_color: None,
        };

        render_badge_svg(&params)
    }

    pub fn render_error(label: &str, message: &str) -> String {
        let params = BadgeParams {
            style: ShieldsBadgeStyle::Flat,
            label: Some(label),
            message: Some(message),
            label_color: None,
            message_color: Some("red"),
            link: None,
            extra_link: None,
            logo: None,
            logo_color: None,
        };

        render_badge_svg(&params)
    }

    fn render_pixel() -> String {
        r#"<svg xmlns="http://www.w3.org/2000/svg" width="1" height="1"/>"#.to_string()
    }

    fn format_with_commas(number: u64) -> String {
        let s = number.to_string();
        let bytes: Vec<_> = s.bytes().rev().collect();
        let chunks: Vec<String> = bytes
            .chunks(3)
            .map(|chunk| String::from_utf8(chunk.iter().copied().collect()).unwrap())
            .collect();
        chunks.join(",").chars().rev().collect()
    }

    fn format_abbreviated(number: u64) -> String {
        let mut num = number as f64;
        let mut idx = 0;

        while num >= 1000.0 && idx < ABBREVIATIONS.len() - 1 {
            num /= 1000.0;
            idx += 1;
        }

        if idx == 0 {
            number.to_string()
        } else {
            format!("{:.1}{}", num, ABBREVIATIONS[idx])
        }
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_format_with_commas() {
        assert_eq!(BadgeRenderer::format_with_commas(0), "0");
        assert_eq!(BadgeRenderer::format_with_commas(999), "999");
        assert_eq!(BadgeRenderer::format_with_commas(1000), "1,000");
        assert_eq!(BadgeRenderer::format_with_commas(1234567), "1,234,567");
    }

    #[test]
    fn test_format_abbreviated() {
        assert_eq!(BadgeRenderer::format_abbreviated(0), "0");
        assert_eq!(BadgeRenderer::format_abbreviated(999), "999");
        assert_eq!(BadgeRenderer::format_abbreviated(1000), "1.0K");
        assert_eq!(BadgeRenderer::format_abbreviated(1500), "1.5K");
        assert_eq!(BadgeRenderer::format_abbreviated(1000000), "1.0M");
        assert_eq!(BadgeRenderer::format_abbreviated(1234567890), "1.2B");
    }

    #[test]
    fn test_badge_style_from_str() {
        assert_eq!(BadgeStyle::from_str("flat"), Some(BadgeStyle::Flat));
        assert_eq!(
            BadgeStyle::from_str("flat-square"),
            Some(BadgeStyle::FlatSquare)
        );
        assert_eq!(BadgeStyle::from_str("plastic"), Some(BadgeStyle::Plastic));
        assert_eq!(
            BadgeStyle::from_str("for-the-badge"),
            Some(BadgeStyle::ForTheBadge)
        );
        assert_eq!(BadgeStyle::from_str("pixel"), Some(BadgeStyle::Pixel));
        assert_eq!(BadgeStyle::from_str("invalid"), None);
    }

    #[test]
    fn test_render_pixel() {
        let svg = BadgeRenderer::render("test", 0, "blue", BadgeStyle::Pixel, false);
        assert!(svg.contains("width=\"1\""));
        assert!(svg.contains("height=\"1\""));
    }
}
