import { historyStoreAdd, historyStoreReplaceLast } from './historyStore.js';
import { pageStoreSet } from './pageStore.js';

import { createStateObject, assumeTemplate } from './utilities.js';

import { loadData } from './loadData.js';

let loading = false;

export async function navigateTo( url, target = {}, replace = false ) {
	if( loading === true ){
		console.log('navigateTo() already loading');
		return false;
	}

	const href = new URL( url );
	if( href.host !== window.location.host ){
		window.open( href, '_blank' );
		return;
	}
	if( url === window.location.href ){
		return false;
	}

	loading = true;

	target.title = target.title || target.pathname;
	target.url = url;
	target.template = target.template || assumeTemplate( href.pathname );

	let state = createStateObject( target );

	// use info provided by page object for

	pageStoreSet({...target, loading: true});

	if( replace === false ){
		history.pushState( state, state.title, state.url);
	} else {
		history.replaceState( state, state.title, state.url);
	}

	historyStoreAdd( state );

	// load data
	let data = await loadData( url );

	// replace info in page object and history
	pageStoreSet({...data , loading: false });

	// naviWorld( entity.worlditem );

	state = createStateObject( data );
	history.replaceState( state, data.title, data.url );
	historyStoreReplaceLast( state );

	loading = false;
}
