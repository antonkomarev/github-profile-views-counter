# GitHub Profile Views Counter

![GitHub Profile Views Counter](https://user-images.githubusercontent.com/1849174/87816378-dfce8480-c86f-11ea-9ac0-2f7907e1d9d4.png)

It counts how many times your GitHub profile has been viewed and displays them in your profile, for free.

![antonkomarev-profile-views-counter](https://user-images.githubusercontent.com/1849174/87852750-78ffa880-c90d-11ea-98d7-eba7b10a09cd.png)

## Usage

Cloud solution launched as 100% free experience. You can [help me cut server costs] if you like this service.

This counter designed to be an analytical instrument for you, but not for people who are visiting your profile.
It could be used to see profile views dynamics as result of publishing new project, blog post or taking part on conference.

If you want to see big numbers in your profile, deploy a standalone solution, and you will not need to write a bot
and spam our server. Everybody knows that any counters could be faked.

> A billion fake views doesn't make you a very popular person, it makes you a person with a billion number in the counter.

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

### Customization

#### Styles

The following styles are available (`flat` is the default):

- `&style=flat` ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=007ec6&style=flat)
- `&style=flat-square` ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=007ec6&style=flat-square)
- `&style=plastic` ![](https://img.shields.io/static/v1?label=Profile+views&message=1234567890&color=007ec6&style=plastic)

```markdown
![](https://komarev.com/ghpvc/?username=your-github-username&style=flat-square)
```

For example

## Is it safe to place tracking image? 

GitHub passes all images URLs through their proxy _camo_ service. URLs are look like [https://camo.githubusercontent.com/a9aa69b588bef281647d53091b4faa01ac126121/68747470733a2f2f6b6f6d617265762e636f6d2f67687076632f3f757365726e616d653d616e746f6e6b6f6d6172657626](https://camo.githubusercontent.com/a9aa69b588bef281647d53091b4faa01ac126121/68747470733a2f2f6b6f6d617265762e636f6d2f67687076632f3f757365726e616d653d616e746f6e6b6f6d6172657626).

This means that only GitHub can track personal information, such as visitors User Agent or IP address.
Third party cloud service stores only views timestamps and total count of views.

## License

- `GitHub Profile Views Counter` application is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].
- `Eye Octicon` hero image licensed under MIT license by [GitHub, Inc].

[Anton Komarev]: https://komarev.com
[GitHub, Inc]: https://github.com
[Live demo]: https://github.com/antonkomarev
[my profile repository]: https://github.com/antonkomarev/antonkomarev
[help me cut server costs]: https://paypal.me/antonkomarev
