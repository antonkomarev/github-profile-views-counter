# GitHub Profile Views Counter

![GitHub Profile Views Counter](https://user-images.githubusercontent.com/1849174/87816378-dfce8480-c86f-11ea-9ac0-2f7907e1d9d4.png)

<p align="center">
<a href="https://discord.gg/geJF43E"><img src="https://img.shields.io/static/v1?logo=discord&label=&message=Discord&color=36393f&style=flat-square" alt="Discord"></a>
<a href="https://github.com/antonkomarev/github-profile-views-counter/blob/master/LICENSE"><img src="https://img.shields.io/github/license/antonkomarev/github-profile-views-counter.svg?style=flat-square" alt="License"></a>
</p>

## Introduction

This counter designed to be an analytical instrument for you, but not for people who are visiting your profile.
It could be used to see profile views dynamics as result of development activity, blogging or taking part in a conference.

It counts how many times your GitHub profile has been viewed and displays them in your profile, for free.

![antonkomarev-profile-views-counter](https://user-images.githubusercontent.com/1849174/87852750-78ffa880-c90d-11ea-98d7-eba7b10a09cd.png)

## Usage

Cloud solution launched as 100% free experience. You can [help me cut server costs] if you like this service.

If you want to see big numbers in your profile, deploy a standalone solution to draw any views count you want
without spamming our server. Everybody knows that any counters could be faked.

> A billion fake profile views doesn't make you a very popular person, it makes you a person with a billion number in the counter.

### Create GitHub profile repository

GitHub magic will happen as soon as you will create a new repository named equally to your username.

![secret-profile-repository](https://user-images.githubusercontent.com/1849174/87852702-f24acb80-c90c-11ea-8247-90ae7de0954d.png)

[Live demo] of [my profile repository].

### Add counter to GitHub profile

You need to add counter in README.md file in your profile repository via Markdown syntax:

```markdown
![](https://komarev.com/ghpvc/?username=your-github-username)
```

> **NOTE**: Don't forget to replace example `username` parameter with real value.

## Make it personal

### Color

You can use any valid HEX color or pick from a predefined set of named colors (`blue` is the default).

| color | demo |
| ----- | ---- |
| `brightgreen` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=44cc11) |
| `green` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=97ca00) |
| `yellow` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=dfb317) |
| `yellowgreen` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=a4a61d) |
| `orange` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=fe7d37) |
| `red` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=e05d44) |
| `blue` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=007ec6) |
| `grey` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=555555) |
| `lightgray` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=9f9f9f) |
| `ff69b4` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=ff69b4) |

**Named color**

```markdown
![](https://komarev.com/ghpvc/?username=your-github-username&color=green)
```

**Hex color**

```markdown
![](https://komarev.com/ghpvc/?username=your-github-username&color=dc143c)
```

> **NOTE**: HEX colors should be used without `#` symbol prefix.

### Styles

The following styles are available (`flat` is the default).

| style | demo |
| ----- | ---- |
| `flat` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=007ec6&style=flat) |
| `flat-square` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=007ec6&style=flat-square) |
| `plastic` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=007ec6&style=plastic) |

```markdown
![](https://komarev.com/ghpvc/?username=your-github-username&style=flat-square)
```

### Label

You can overwrite default `Profile views` text with your own label.

![](https://img.shields.io/static/v1?label=PROFILE+VIEWS&message=1234567890&color=007ec6)

```markdown
![](https://komarev.com/ghpvc/?username=your-github-username&label=PROFILE+VIEWS)
```

> **NOTE**: Replace whitespace with `+` character in multi-word labels.

## FAQ

### Isn't that a security breach? 

GitHub passes all images URLs through their [GitHub Camo proxy service](https://github.blog/2010-11-13-sidejack-prevention-phase-3-ssl-proxied-assets/).
This means that only GitHub can track personal information, such as visitors User Agent or IP address.
Third party cloud service stores only views timestamps and total count of views.

### Are you making money on it?

No. Only spending them. You can [become a sponsor] if you want it to keep running & receive new features.

## License

- `GitHub Profile Views Counter` application is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].
- `Eye Octicon` hero image licensed under MIT license by [GitHub, Inc].

[Anton Komarev]: https://komarev.com
[GitHub, Inc]: https://github.com
[Live demo]: https://github.com/antonkomarev
[my profile repository]: https://github.com/antonkomarev/antonkomarev
[help me cut server costs]: https://paypal.me/antonkomarev
[become a sponsor]: https://paypal.me/antonkomarev
