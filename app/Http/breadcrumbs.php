<?php

// Admin
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Admin',url('admin'));
});

// Admin > Lexicons
Breadcrumbs::register('lexicons', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Lexicons',url('admin/lexicon/list'));
});

// Home > Lexicons > [Lexicon]
Breadcrumbs::register('lexicon', function($breadcrumbs,$lexiconCode,$lexiconName)
{
    $breadcrumbs->parent('lexicons');
    $breadcrumbs->push($lexiconName, url('admin/lexicon/view', $lexiconCode));
});

// Home > Lexicons > [Lexicon] > [lexiconItem]
Breadcrumbs::register('lexiconItem', function($breadcrumbs,$versionCode,$versionName,$verseNumber)
{
    $breadcrumbs->parent('lexicon',$versionCode,$versionName);
    $breadcrumbs->push($verseNumber);
});

// Admin > Bibles
Breadcrumbs::register('bibles', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Bibles', url('admin/bible/versions'));
});

// Home > Bibles > [Version]
Breadcrumbs::register('version', function($breadcrumbs, $versionCode,$versionName)
{
    $breadcrumbs->parent('bibles');
    $breadcrumbs->push($versionName, url('admin/bible/verses',$versionCode));
});

// Home > Bibles > [Version] > [Verse]
Breadcrumbs::register('verse', function($breadcrumbs,$versionCode,$versionName,$verseNumber)
{
    $breadcrumbs->parent('version',$versionCode,$versionName);
    $breadcrumbs->push($verseNumber);
});

// Admin > Users
Breadcrumbs::register('users', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Users', url('admin/user/list'));
});

// Admin > Users > [CreateUser]
Breadcrumbs::register('userCreate', function($breadcrumbs)
{
    $breadcrumbs->parent('users');
    $breadcrumbs->push("Create new user");
});

// Admin > Users > [UpdateUser]
Breadcrumbs::register('userUpdate', function($breadcrumbs, $user)
{
    $breadcrumbs->parent('users');
    $breadcrumbs->push('Edit '.$user->name);
});

