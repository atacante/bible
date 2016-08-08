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
    $breadcrumbs->push('Edit user');
});

// Admin > Locations
Breadcrumbs::register('locations', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Locations', url('admin/location/list'));
});

// Admin > Locations > [CreateLocation]
Breadcrumbs::register('locationCreate', function($breadcrumbs)
{
    $breadcrumbs->parent('locations');
    $breadcrumbs->push("Create new location");
});

// Admin > Locations > [UpdateLocation]
Breadcrumbs::register('locationUpdate', function($breadcrumbs)
{
    $breadcrumbs->parent('locations');
    $breadcrumbs->push('Edit location');
});

// Admin > People
Breadcrumbs::register('peoples', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('People', url('admin/peoples/list'));
});

// Admin > People > [CreatePeople]
Breadcrumbs::register('peopleCreate', function($breadcrumbs)
{
    $breadcrumbs->parent('peoples');
    $breadcrumbs->push("Create new people");
});

// Admin > People > [UpdatePeople]
Breadcrumbs::register('peopleUpdate', function($breadcrumbs)
{
    $breadcrumbs->parent('peoples');
    $breadcrumbs->push('Edit people');
});

// Admin > Coupons
Breadcrumbs::register('coupons', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Coupons', url('admin/coupons/list'));
});

// Admin > Coupons > [CreateCoupon]
Breadcrumbs::register('couponCreate', function($breadcrumbs)
{
    $breadcrumbs->parent('coupons');
    $breadcrumbs->push("Create new coupon");
});

// Admin > Coupons > [UpdateCoupon]
Breadcrumbs::register('couponUpdate', function($breadcrumbs)
{
    $breadcrumbs->parent('coupons');
    $breadcrumbs->push('Edit coupon');
});

// Admin > Categories
Breadcrumbs::register('categories', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Categories', url('admin/categories/list'));
});

// Admin > Categories > [CreateCategory]
Breadcrumbs::register('categoryCreate', function($breadcrumbs)
{
    $breadcrumbs->parent('categories');
    $breadcrumbs->push("Create new category");
});

// Admin > Categories > [UpdateCategory]
Breadcrumbs::register('categoryUpdate', function($breadcrumbs)
{
    $breadcrumbs->parent('categories');
    $breadcrumbs->push('Edit category');
});

// Admin > Articles
Breadcrumbs::register('articles', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Articles', url('admin/articles/list'));
});

// Admin > Articles > [CreateArticle]
Breadcrumbs::register('articleCreate', function($breadcrumbs)
{
    $breadcrumbs->parent('articles');
    $breadcrumbs->push("Create new article");
});

// Admin > Articles > [UpdateArticle]
Breadcrumbs::register('articleUpdate', function($breadcrumbs)
{
    $breadcrumbs->parent('articles');
    $breadcrumbs->push('Edit article');
});

// Admin > ShopCategories
Breadcrumbs::register('shop-categories', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('ShopCategories', url('admin/shop-categories/list'));
});

// Admin > ShopCategories > [CreateShopCategory]
Breadcrumbs::register('shop-categoryCreate', function($breadcrumbs)
{
    $breadcrumbs->parent('shop-categories');
    $breadcrumbs->push("Create new category");
});

// Admin > ShopCategories > [UpdateShopCategory]
Breadcrumbs::register('shop-categoryUpdate', function($breadcrumbs)
{
    $breadcrumbs->parent('shop-categories');
    $breadcrumbs->push('Edit category');
});

// Admin > Products
Breadcrumbs::register('shop-products', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Products', url('admin/shop-products/list'));
});

// Admin > Products > [CreateProduct]
Breadcrumbs::register('productCreate', function($breadcrumbs)
{
    $breadcrumbs->parent('shop-products');
    $breadcrumbs->push("Create new product");
});

// Admin > Products > [UpdateProduct]
Breadcrumbs::register('productUpdate', function($breadcrumbs) {
    $breadcrumbs->parent('shop-products');
    $breadcrumbs->push('Edit product');
});

// Admin > CMS
Breadcrumbs::register('cms', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('CMS', url('admin/cms/list'));
});

// Admin > CMS > [UpdateCMS]
Breadcrumbs::register('cmsUpdate', function($breadcrumbs)
{
    $breadcrumbs->parent('cms');
    $breadcrumbs->push('Edit pages');
});

// Admin > Subscription
Breadcrumbs::register('subscription', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Subscription', url('admin/subscription/list'));
});

// Admin > Coupons
Breadcrumbs::register('complaints', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Complaints', url('admin/complaints/list'));
});

// Admin > Complaints > [UpdateComplaints]
Breadcrumbs::register('complaintUpdate', function($breadcrumbs)
{
    $breadcrumbs->parent('complaints');
    $breadcrumbs->push('Edit complaint');
});