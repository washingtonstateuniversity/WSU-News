# [WSU News](http://news.wsu.edu/)

A close representation of the current state of the Washington State University news site.

This repository is provided to track code base changes to `news.wsu.edu` over time as it exists as an individual installation of WordPress at WSU.

## Plugins

Plugins that should be installed along with `news.wsu.edu`:

* Admin Color Schemes
* Safe Redirect Manager
* WordPress SEO
* WSU News Announcements
* WSUWP SSO Authentication

## Themes

The current WSU News theme is a child theme of TwentyThirteen.

## Local Development

This repository is setup to work with the [WSUWP Indie Development](https://github.com/washingtonstateuniversity/WSUWP-Indie-Development) environment.

1. Install [VirtualBox](http://virtualbox.org)
1. Install [Vagrant](http://vagrantup.com)
1. Clone [WSUWP Indie Development](https://github.com/washingtonstateuniversity/WSUWP-Indie-Development)
1. Clone [WSU News](https://github.com/washingtonstateuniversity/WSU-News) into the `www/` directory of that environment.
1. Type `vagrant plugin install vagrant-hostsupdater`
1. Type `vagrant plugin install vagrant-hosts`
1. Type `vagrant up`
1. Visit `http://dev.news.wsu.edu` in your browser.
