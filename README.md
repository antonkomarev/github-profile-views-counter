# GitHub Profile Views Counter

It counts how many times your profile has been viewed.

![Profile View Counter Example](https://user-images.githubusercontent.com/1849174/87232647-40ba1080-c3c9-11ea-9d50-c6778edcd8c7.png)

## Usage

Create your GitHub Profile repository. GitHub magic will happen as soon as you will create a new repository named equally to your username.

Here is my profile repository: [https://github.com/antonkomarev/antonkomarev](https://github.com/antonkomarev/antonkomarev)

### Out of the box

You need to add counter in README.md file in your profile repository via Markdown syntax:

```markdown
![](https://komarev.com/ghpvc/?username=antonkomarev)
```

> Don't forget to replace example `username` with your own

It is completely free. You can [help me cut server costs](https://paypal.me/antonkomarev) if you like this service. We don't track any personal information since GitHub proxies all images.  

### Self-hosted

Only `public/index.php` should be exposed.

This URL will render SVG image with profile views counter and will increment it on each view of your profile.

By default, this application using file-repository and counters stored in `storage/{$username}-views-count` files.

Optionally you could switch to database-repository, then you will have to copy `.env.example` file to `.env` and fill in database configuration.

You need to host this app on your server and display it via Markdown syntax:

```markdown
![](https://your.application.path/?username=your-github-username)
``` 

## License

- `GitHub Profile Views Counter` application is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].

[Anton Komarev]: https://komarev.com
