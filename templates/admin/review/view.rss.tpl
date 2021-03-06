{* purpose of this template: reviews rss feed in admin area *}
{reviewsTemplateHeaders contentType='application/rss+xml'}<?xml version="1.0" encoding="{charset assign='charset'}{if $charset eq 'ISO-8859-15'}ISO-8859-1{else}{$charset}{/if}" ?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:atom="http://www.w3.org/2005/Atom">
{*<rss version="0.92">*}
{gt text='Latest reviews' assign='channelTitle'}
{gt text='A direct feed showing the list of reviews' assign='channelDesc'}
    <channel>
        <title>{$channelTitle}</title>
        <link>{$baseurl|escape:'html'}</link>
        <atom:link href="{php}echo substr(\System::getBaseURL(), 0, strlen(\System::getBaseURL())-1);{/php}{getcurrenturi}" rel="self" type="application/rss+xml" />
        <description>{$channelDesc} - {$modvars.ZConfig.slogan}</description>
        <language>{lang}</language>
        {* commented out as $imagepath is not defined and we can't know whether this logo exists or not
        <image>
            <title>{$modvars.ZConfig.sitename}</title>
            <url>{$baseurl|escape:'html'}{$imagepath}/logo.jpg</url>
            <link>{$baseurl|escape:'html'}</link>
        </image>
        *}
        <docs>http://blogs.law.harvard.edu/tech/rss</docs>
        <copyright>Copyright (c) {php}echo date('Y');{/php}, {$baseurl}</copyright>
        <webMaster>{$modvars.ZConfig.adminmail|escape:'html'} ({usergetvar name='uname' uid=2})</webMaster>

{foreach item='review' from=$items}
    <item>
        <title><![CDATA[{if isset($review.updatedDate) && $review.updatedDate ne null}{$review.updatedDate|dateformat} - {/if}{$review->getTitleFromDisplayPattern()|notifyfilters:'reviews.filterhook.reviews'}]]></title>
        <link>{modurl modname='Reviews' type='admin' func='display' ot='review' id=$review.id slug=$review.slug fqurl='1'}</link>
        <guid>{modurl modname='Reviews' type='admin' func='display' ot='review' id=$review.id slug=$review.slug fqurl='1'}</guid>
        {if isset($review.createdUserId)}
            {usergetvar name='uname' uid=$review.createdUserId assign='cr_uname'}
            {usergetvar name='name' uid=$review.createdUserId assign='cr_name'}
            <author>{usergetvar name='email' uid=$review.createdUserId} ({$cr_name|default:$cr_uname})</author>
        {/if}

        <category><![CDATA[{gt text='Categories'}: {foreach name='categoryLoop' key='propName' item='catMapping' from=$review.categories}{$catMapping.category.name|safetext}{if !$smarty.foreach.categoryLoop.last}, {/if}{/foreach}]]></category>

        <description>
            <![CDATA[
            {$review.text|replace:'<br>':'<br />'}
            ]]>
        </description>
        {if isset($review.createdDate) && $review.createdDate ne null}
            <pubDate>{$review.createdDate|dateformat:"%a, %d %b %Y %T +0100"}</pubDate>
        {/if}
    </item>
{/foreach}
    </channel>
</rss>
