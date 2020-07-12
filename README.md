# GitHub Profile Views Counter

It counts how many times your profile has been viewed.

![Profile View Counter Example](https://user-images.githubusercontent.com/1849174/87232647-40ba1080-c3c9-11ea-9d50-c6778edcd8c7.png)

## Usage

Create your GitHub Profile repository. GitHub magic will happen as soon as you will create a new repository named equally to your username.

![](https://user-images.githubusercontent.com/1849174/87251639-cbf0e000-c475-11ea-9c69-7600c78ebe33.png)

My profile repository: [https://github.com/antonkomarev/antonkomarev](https://github.com/antonkomarev/antonkomarev)

### Cloud application

You need to add counter in README.md file in your profile repository via Markdown syntax:

```markdown
![](https://komarev.com/ghpvc/?username=your-github-username)
```

> Don't forget to replace example `username` with your own

This service launched as experiment. It is completely free. You can [help me cut server costs](https://paypal.me/antonkomarev) if you like this service. I don't track any personal information since GitHub proxies all images.  

### Self-hosted application

Only `public/index.php` should be exposed.

You need to host this app on your server and display it via Markdown syntax:

```markdown
![](https://your.application.path/?username=your-github-username)
``` 

This URL will render SVG image with profile views counter and will increment it on each view of your profile.

#### Self-hosted application customization

By default, this application using file repository, visits log stored in `storage/{$username}-views` and counter stored in `storage/{$username}-views-count` file.

You can switch to database repository, then you will have to copy `.env.example` file to `.env` and fill in database configuration.

## License

- `GitHub Profile Views Counter` application is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].

[Anton Komarev]: https://komarev.com
