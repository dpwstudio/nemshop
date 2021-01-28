{capture name=path}{l s='paiement au magasin' mod='magasin'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Récapitulatif de votre panier' mod='magasin'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if isset($nbProducts) && $nbProducts <= 0}
	<p class="warning">{l s='Votre panier est vide.' mod='magasin'}</p>
{else}

<h3>{l s='Paiement au restaurant' mod='magasin'}</h3>
<form action="{$link->getModuleLink('magasin', 'validation', [], true)|escape:'html'}" method="post">
	<p>
		<img src="{$this_path_magasin}magasin.png" alt="{l s='paiement au magasin' mod='magasin'}" width="86" height="86" style="float:left; margin: 0px 10px 5px 0px;" />
		{l s='Vous avez choisi de réaliser un paiement au restaurant.' mod='magasin'}
		<br/><br />
		{l s='En voici un bref récapitulatif :' mod='magasin'}
	</p>
	<p style="margin-top:20px;">
		- {l s='Le montant total de votre paiement au restaurant est de :' mod='magasin'}
		<span id="amount" class="price">{displayPrice price=$total}</span>
		{if $use_taxes == 1}
			{l s='(taxes incl.)' mod='magasin'}
		{/if}
	</p>
	<p>
		<br /><br />
		<b>{l s='Merci de confirmer votre paiement au magasin en cliquant sur \'Je confirme mon paiement au restaurant\'' mod='magasin'}.</b>
	</p>
	<p class="cart_navigation clearfix" id="cart_navigation">
			<a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html'}" class="button-exclusive btn btn-default">
				<i class="icon-chevron-left"></i>Autres moyens de paiement
			</a>
			<button type="submit" class="button btn btn-default button-medium">
				<span>Je confirme ma commande<i class="icon-chevron-right right"></i></span>
			</button>
	</p>
</form>
{/if}
