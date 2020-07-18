# GitHub Profile Views Counter

![GitHub Profile Views Counter](https://user-images.githubusercontent.com/1849174/87816378-dfce8480-c86f-11ea-9ac0-2f7907e1d9d4.png)

It counts how many times your GitHub profile has been viewed and displays them in your profile, for free.

![Profile View Counter Example](https://user-images.githubusercontent.com/1849174/87232647-40ba1080-c3c9-11ea-9d50-c6778edcd8c7.png)

## Usage

Cloud solution launched as 100% free experience. You can [help me cut server costs] if you like this service.

### Create GitHub profile repository

GitHub magic will happen as soon as you will create a new repository named equally to your username.

![](https://user-images.githubusercontent.com/1849174/87251639-cbf0e000-c475-11ea-9c69-7600c78ebe33.png)

My profile repository: [https://github.com/antonkomarev/antonkomarev](https://github.com/antonkomarev/antonkomarev)

### Add counter to GitHub profile

You need to add counter in README.md file in your profile repository via Markdown syntax:

```markdown
![](https://komarev.com/ghpvc/?username=your-github-username)
```

> **NOTE**: Don't forget to replace example `username` parameter with real value.

> GitHub will proxy this URL via its _camo_ service, so it will look like [https://camo.githubusercontent.com/a9aa69b588bef281647d53091b4faa01ac126121/68747470733a2f2f6b6f6d617265762e636f6d2f67687076632f3f757365726e616d653d616e746f6e6b6f6d6172657626](https://camo.githubusercontent.com/a9aa69b588bef281647d53091b4faa01ac126121/68747470733a2f2f6b6f6d617265762e636f6d2f67687076632f3f757365726e616d653d616e746f6e6b6f6d6172657626).
> That means I can't track any personal information like visitors User Agent or IP address. Only views timestamp and total count of views are stored.  

## License

- `GitHub Profile Views Counter` application is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].
- `Eye Octicon` hero image licensed under MIT license by [GitHub, Inc].

[Anton Komarev]: https://komarev.com
[GitHub, Inc]: https://github.com
[help me cut server costs]: https://paypal.me/antonkomarev
