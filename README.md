# JAXON

Supercharge your JSON API's with JAXON (**J**SON **A**PI HTM**X** **O**bject **N**otation) and HTMX

## About

JAXON is a middleman between you JSON API and your frontend application. It allows you to define what you want the HTML data to return as with data from your API.

## Usage

To use **JAXON** with HTMX all you need to do is define the HTML structure in a `textarea` and the URL in an `input`.

```html
<button hx-post="/jaxon.php" hx-include="#data, #link" hx-target="#res">post</button>
<input type="hidden" name="link" id="link" value="https://swapi.dev/api/people/1/?format=json">
<textarea hidden name="config" id="data" cols="30" rows="10">
    {
        "div": [
            {
                "h1": {
                    "text": "name"
                }
            },
            {
                "p": {
                    "text": "height"
                }
            },
            {
                "p": {
                    "text": "birth_year"
                }
            }
        ]
    }

</textarea>
<div id="res"></div>
```

## Restrictions

For security reasons, only a set ammount of tags are added by default and its very easy to extend them.

## Installations

JAXON is written in PHP so you can run it on any lamp/lnmp server or even locally with:

```
php -S localhost:<port>
```
or 
```
php artisan serve
```

**Note:** Create an issue or pull request if you would like it written in another language.