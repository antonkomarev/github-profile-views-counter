# GitHub Profile Views Counter

![GitHub Profile Views Counter](https://user-images.githubusercontent.com/1849174/87816378-dfce8480-c86f-11ea-9ac0-2f7907e1d9d4.png)

<p align="center">
<a href="https://discord.gg/geJF43E"><img src="https://img.shields.io/static/v1?logo=discord&label=&message=Discord&color=36393f&style=flat-square" alt="Discord"></a>
<a href="https://github.com/antonkomarev/github-profile-views-counter/blob/master/LICENSE"><img src="https://img.shields.io/github/license/antonkomarev/github-profile-views-counter.svg?style=flat-square" alt="License"></a>
</p>

## Introduction

Try [Ÿ HŸPE] service as the more robust and feature rich solution.

GHPVС project is proof of concept. This counter designed to be an analytical instrument for you, but not for people who are visiting your profile.
It could be used to see profile views dynamics as result of development activity, blogging or taking part in a conference.

It counts how many times your GitHub profile has been viewed and displays them in your profile, for free.

![antonkomarev-profile-views-counter](https://user-images.githubusercontent.com/1849174/88077155-9ccc2400-cb83-11ea-8d9c-d18a8b1dc297.png)

## Usage

Cloud solution launched as 100% free experience. [Help me cut server costs] if you like this service.

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

> **NOTE**: Don't forget to replace example `your-github-username` parameter with real value.

## Make it personal

### Color

You can use any valid HEX color or pick from a predefined set of named colors (`blue` is the default).

| color | demo |
| ----- | ---- |
| `brightgreen` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=brightgreen) |
| `green` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=green) |
| `yellow` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=yellow) |
| `yellowgreen` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=yellowgreen) |
| `orange` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=orange) |
| `red` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=red) |
| `blue` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=blue) |
| `grey` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=grey) |
| `lightgrey` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=lightgrey) |
| `blueviolet` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=blueviolet) |
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
| `for-the-badge` | ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=007ec6&style=for-the-badge) |

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

### Can I see detailed statistics?

This project provides minimalistic counter only. Use [Ÿ HŸPE] service if you want to get detailed info about:
- user profile views
- user followers history
- repository stars history
- repository traffic for longer than 14 days

### How to reset counter?

To reset counter you should login to the [Ÿ HŸPE] service and then you will be able to reset counter on the https://yhype.me/ghpvc page.

### Why does the counter increase every time the page is reloaded?

This is counter of profile views (page hits), not a counter of unique visitors.
There is no way to get the username, browser user agent or IP address of the visitor because
GitHub proxies all image URLs through the [GitHub Camo service].
In other words, we can only increment the counter for each request from the GitHub proxy server,
we don't know who initiated it.

### Are you making money on it?

No. Only spending it. [Become a sponsor] if you want it to keep running & receive new features.

## Alternatives

- [Ÿ HŸPE] enhanced GitHub professional account statistics & analytics

## License

- `GitHub Profile Views Counter` application is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].
- `Eye Octicon` hero image licensed under MIT license by [GitHub, Inc].

## 🌟 Stargazers over time

[![Stargazers over time](https://chart.yhype.me/github/repository-star/v1/MDEwOlJlcG9zaXRvcnkyNzg5Mjk4Njc=.svg)](https://yhype.me?utm_source=github&utm_medium=antonkomarev-github-profile-views-counter&utm_content=chart-repository-star-cumulative)

[Anton Komarev]: https://komarev.com
[GitHub, Inc]: https://github.com
[Live demo]: https://github.com/antonkomarev
[my profile repository]: https://github.com/antonkomarev/antonkomarev
[Help me cut server costs]: https://paypal.me/antonkomarev
[Become a sponsor]: https://paypal.me/antonkomarev
[GitHub Camo service]: https://github.blog/2010-11-13-sidejack-prevention-phase-3-ssl-proxied-assets/
[Ÿ HŸPE]: https://yhype.me
