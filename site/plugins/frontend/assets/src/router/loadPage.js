import { historyStoreAdd, historyStoreReplaceLast } from './historyStore.js';
import { pageStore } from './pageStore.js';

import { createStateObject, assumeTemplate } from './utilities.js';

import { loadData } from './loadData.js';

export async function loadPage( url = false, title = false ) {

	let state = createStateObject({
		title: title || document.title.replace('CDA ',''),
		url: url || window.location.href,
		template: assumeTemplate( window.location.pathname )
	});

	pageStore.set({...state, loading: true});
	historyStoreAdd( state );

	// load data
	let data = await loadData( state.url );

	// replace info in page object and history
	pageStore.set({...data , loading: false });

	// naviWorld( entity.worlditem );
	// relocate();

	state = createStateObject( data );
	history.replaceState( state, state.title, state.url );
	historyStoreReplaceLast( state );

}
