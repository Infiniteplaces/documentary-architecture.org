<script>

	export let entity;
	export let tab;
	export let category;

  import CollectionCards from '../collection/cards.svelte';
	import CollectionList from '../collection/list.svelte';
	import EntityInfo from '../collection/entityinfo.svelte';
	import CollectionsGallery from '../collection/gallery.svelte';

	let layout = {
		cards: CollectionCards,
		list: CollectionList,
		entityinfo: EntityInfo,
		gallery: CollectionsGallery
	}

</script>

<section class="tab collection {tab.layout}">

	{#if tab.headline}
    	<h3 class="section--header">{tab.headline}</h3>
	{/if}

    <div class="section--content">

    	<svelte:component this={layout[ tab.layout ]} list={tab.content} category={category}/>

    </div>

</section>
