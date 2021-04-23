<?php
$h = 'The &lt;b&gt; tag makes text bold: <code>&lt;b&gt;bold&lt;/b&gt;</code>';
print preg_replace_callback('@<code>(.*?)</code>@', 'decode', $h);

function decode($matches) {
    return html_entity_decode($matches[1]);
}