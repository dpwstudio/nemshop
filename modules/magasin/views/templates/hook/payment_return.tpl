	<p>{l s='Votre commande sur %s est terminée.' sprintf=$shop_name mod='magasin'}
		<br /><br />
		<strong>Vous pouvez la retrouver dans la zone "Mon compte/Historique et détails de mes commandes".</strong>
		<br /><br />{l s='Pour davantage de questions, merci de contacter notre' mod='magasin'} <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='Service client.' mod='magasin'}</a>.
	</p>