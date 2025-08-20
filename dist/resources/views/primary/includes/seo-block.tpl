{* SEO blocks *}
{block name="metakeywords"}{if !$rewrite}{$obj.meta_keywords}{else}{$obj2.meta_keywords}{/if}{/block}

{block name="metadescription"}{if !$rewrite}{$obj.meta_description}{else}{$obj2.meta_description}{/if}{/block}

{block name="title"}{if isset($obj) && $obj.title != ''}{$obj.title}{else}{$obj2.title}{/if}{/block}

{* /SEO blocks *}