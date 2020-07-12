# GitHub Profile Views Counter

It counts how many times your profile has been viewed.

![Profile View Counter Example](https://user-images.githubusercontent.com/1849174/87232647-40ba1080-c3c9-11ea-9d50-c6778edcd8c7.png)

## Usage

Create your GitHub Profile repository. GitHub magic will happen as soon as you will create a new repository named equally to your username.

Here is my profile repository: [https://github.com/antonkomarev/antonkomarev](https://github.com/antonkomarev/antonkomarev)

You need to host this app on your server and display it via Markdown syntax:

```markdown
![](https://komarev.com/github-profile-views-counter/?username=antonkomarev)
```

Only `public/index.php` should be exposed.

This URL will render SVG image with profile views counter and will increment it on each view of your profile.

Counter stored in `storage/views-count` file.

## License

- `GitHub Profile Views Counter` application is open-sourced software licensed under the [MIT license](LICENSE) by [Anton Komarev].

[Anton Komarev]: https://komarev.com
