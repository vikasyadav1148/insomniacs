<?php
/*
Template Name: Trends Page
*/

/*
$trends_popular_keywords_not_found = array();

$trends_keyword_description_part1_template = 'Browse the best %term% movies and TV shows, featuring top-rated films, popular series, and hidden gems. Discover content based on this theme and find your next must-watch.';

$trends_popular_keyword_labels = array(
	'Time Travel',
	'Zombie Apocalypse',
	'Serial Killer',
	'Alien Invasion',
	'Post-Apocalyptic',
	'Survival',
	'Artificial Intelligence (a.i.)',
	'Dystopia',
	'Space Exploration',
	'Parallel World',
	'alternate timeline',
	'Supernatural',
	'Ghost',
	'Demon',
	'Vampire',
	'Werewolf',
	'Witchcraft',
	'Haunted House',
	'Monster',
	'Creature Feature',
	'Heist',
	'Revenge',
	'Prison Escape',
	'Bank Robbery',
	'Based on True Story',
	'Spy',
	'Espionage',
	'Assassin',
	'Hitman',
	'Police Investigation',
	'Detective',
	'Murder Mystery',
	'true Crime',
	'Psychological Thriller',
	'Courtroom Drama',
	'Political Thriller',
	'Conspiracy',
	'Corruption',
	'Gangster',
	'Mafia',
	'Teen Drama',
	'High School',
	'Coming of Age',
	'Family Drama',
	'Romance',
	'Love Triangle',
	'Friendship',
	'Small Town',
	'Mystery',
	'Crime Investigation',
	'Police Drama',
	'Medical Drama',
	'legal thriller',
	'Workplace',
	'Reality TV',
	'Vigilante',
	'Dark Secret',
	'Hidden Identity',
	'Double Life',
	'Post-Traumatic Stress disorder (ptsd)',
	'Romantic',
	'Dark Comedy',
	'Satire',
	'Parody',
	'Buddy Comedy',
	'Based on Children\'s book',
	'Feel Good',
	'Holiday',
	'Christmas',
	'Summer',
	'Road Trip',
	'Adventure',
	'Treasure Hunt',
	'Western',
	'Marvel Cinematic Universe (mcu)',
	'Based on novel or book',
	'Underdog',
	'Medieval',
	'Second Chance',
	'Fish Out of Water',
	'Soldier',
	'Martial Arts',
	'Superhero',
	'War',
	'Military',
	'Special Forces',
	'Rescue Mission',
	'Based on Comic',
	'Disaster',
	'Natural Disaster',
	'Based on video game.',
	'Exorcism',
	'Chase',
	'Manhunt',
	'Battle',
	'Sword Fighting',
	'Gladiator',
	'Vikings (norsemen)',
	'Pirate',
	'Time Loop',
	'Criminal',
	'Mind Control',
	'Memory Loss',
	'Amnesia',
	'Dream',
	'Cult',
	'DC Universe (dcu)',
	'World War ii',
	'Dramatic',
	'Doppelgänger',
	'Clone',
	'Experiment',
	'Scientist',
	'Drugs',
	'Hacker',
	'Technology',
	'Future',
	'Space Colony',
	'Alien Planet',
);

$trends_popular_keyword_part2_by_label = array(
	'Time Travel'              => 'Jump across eras, rewrite fate, face paradoxes, or race the clock when past and future collide.',
	'Zombie Apocalypse'        => 'Survive hordes of the undead, rebuild society, lose trust, or watch civilization crumble overnight.',
	'Serial Killer'            => 'Follow obsessive patterns, hunt predators, decode psychology, or fear who walks beside you unseen.',
	'Alien Invasion'           => 'Defend Earth, decode signals, witness first contact, or face technology and motives beyond understanding.',
	'Post-Apocalyptic'         => 'Scavenge wastelands, cling to hope, battle factions, or rebuild meaning after the world ends.',
	'Survival'                 => 'Endure the elements, scarce supplies, isolation, or rivals when every hour is a test of will.',
	'Artificial Intelligence (a.i.)'  => 'Question consciousness, lose control to machines, chase breakthroughs, or blur creator and creation.',
	'Dystopia'                 => 'Resist surveillance states, forbidden truth, broken systems, or citizens who dare to remember freedom.',
	'Space Exploration'        => 'Chart the void, dock with wonder, face cosmic risk, or push humanity past every known horizon.',
	'Parallel world'        => 'Meet alternate selves, split timelines, collide worlds, or wonder which reality you truly belong to.',
	'alternate timeline'        => 'Slip into skewed rules, unstable physics, or lives that almost match yours but never quite do.',
	'Supernatural'             => 'Confront powers beyond science, omens, rituals, or forces that refuse to stay legend.',
	'Ghost'                   => 'Hear footsteps in empty halls, unfinished vows, cold drafts, or presences that demand to be heard.',
	'Demon'                   => 'Face possession, ancient evil, exorcism, or bargains that cost more than a soul can pay.',
	'Vampire'                 => 'Walk eternal nights, hunger for blood, court immortality, or love someone who never ages.',
	'Werewolf'               => 'Feel the moon change you, hunt as beast, hide the curse, or choose pack over peace.',
	'Witchcraft'               => 'Cast forbidden rites, join covens, pay prices in power, or stir old magic sleeping underground.',
	'Haunted House'            => 'Creaking floorboards, trapped spirits, rooms that remember, or architecture that will not let you leave.',
	'Monster'                  => 'Track legends made flesh, fear what lurks below, or survive what science swears cannot exist.',
	'Creature Feature'         => 'Rubber reality, practical terrors, camp and dread, or beasts that steal every scene they crawl into.',
	'Heist'                    => 'Assemble crews, crack vaults, split loot, or discover the real score was never the gold.',
	'Revenge'                  => 'Settle old scores, burn bridges, weigh justice against ruin, or become what you swore to destroy.',
	'Prison Escape'            => 'Dig tunnels, bribe guards, outsmart systems, or risk everything for one breath of free air.',
	'Bank Robbery'             => 'Masks and timing, hostages and alarms, inside jobs, or one job that unravels every plan.',
	'Based on True Story'      => 'See real lives dramatized, headlines reborn, facts bent by memory, or truth stranger than fiction.',
	'Spy'                      => 'Swap passports, dead drops, seduce secrets, or vanish before anyone learns your real name.',
	'Espionage'                => 'Moles inside agencies, coded messages, shifting allegiances, or nations gambling on a single lie.',
	'Assassin'                 => 'Clean shots, ghost contracts, moral fatigue, or targets who refuse to stay dead in your head.',
	'Hitman'                   => 'Professional calm, strict codes, bloody invoices, or one job that breaks every rule you live by.',
	'Police Investigation'     => 'Bag evidence, chase leads, pressure witnesses, or follow instinct when the case goes cold.',
	'Detective'                => 'Connect clues, interrogate charm, read rooms, or solve puzzles everyone else calls coincidence.',
	'Murder Mystery'           => 'Red herrings, locked rooms, motives stacked like cards, or killers hiding in plain sight.',
	'true Crime'           => 'Street-level danger, crooked deals, ticking clocks, or law and lawless trading the same dark streets.',
	'Psychological Thriller'   => 'Doubt your senses, unreliable allies, mind games, or terror that starts behind your own eyes.',
	'Courtroom Drama'          => 'Objections and revelations, juries swayed by a phrase, or verdicts that rewrite whole lives.',
	'Political Thriller'       => 'Leaks, assassinations, backroom trades, or democracy held together with secrets and fear.',
	'Conspiracy'               => 'Chase threads no one believes, disappearances, cover-ups, or truth buried under too many signatures.',
	'Corruption'               => 'Rot inside institutions, bought silence, whistleblowers at risk, or power sold by the inch.',
	'Gangster'                 => 'Territory wars, loyalty tests, flashy suits, or empires built on blood and briefcases.',
	'Mafia'                    => 'Omertà, family feasts with knives under tables, respect priced in favors, or honor that kills.',
	'Teen Drama'               => 'First hearts broken, hallways that judge, identity forming fast, or adulthood arriving too soon.',
	'High School'              => 'Cliques, exams, prom pressure, or the small stage where every rumor feels like the world.',
	'Coming of Age'            => 'First freedoms, painful lessons, summer nights, or the season you realize who you might become.',
	'Family Drama'             => 'Holiday blowups, inherited grudges, love that bruises, or bonds that refuse to break cleanly.',
	'Romance'            => 'Longing, sacrifice, timing that hurts, or love stories that cost more than fairy tales admit.',
	'Love Triangle'            => 'Choose between hearts, jealous silence, stolen glances, or three people sharing one breaking point.',
	'Friendship'               => 'Loyalty tested, laughter that heals, drift and reunion, or friends who become family by accident.',
	'Small Town'               => 'Everyone knows your business, buried scandals, slow roads, or evil wearing a neighborly smile.',
	'Mystery'                  => 'Locked doors, missing pages, questions that multiply, or answers that change what you trusted.',
	'Crime Investigation'      => 'Forensics and instinct, cold cases warming, suspects rotating, or justice chasing a moving target.',
	'Police Drama'             => 'Shift work, precinct politics, sirens at dusk, or badges carried like both shield and weight.',
	'Medical Drama'            => 'Triage choices, scalpels and ethics, lives on monitors, or healers who cannot save themselves.',
	'legal thriller'              => 'Billable hours, moral lines, courtroom theater, or clients who blur victim and villain.',
	'Workplace'          => 'Corner offices, sabotage by email, ambition as religion, or careers built on quiet betrayals.',
	'Reality TV'               => 'Confessionals, manufactured drama, alliances, or cameras that turn ordinary rooms into arenas.',
	'Hidden Identity'          => 'Double names, forged papers, love built on lies, or masks that fuse to skin.',
	'Double Life'              => 'Two phones, two cities, two families, or one slip away from losing both worlds at once.',
	'Dark Secret'              => 'Skeletons in attics, whispered names, pasts that knock late, or truth that burns whoever opens it.',
	'Post-Traumatic Stress disorder (ptsd)' => 'Trauma echoes, flashbacks, survival after the storm, or minds reliving battles long after the cameras stop.',
	'Romantic'                 => 'Longing, stolen glances, timing that hurts, or love stories that cost more than fairy tales admit.',
	'Dark Comedy'              => 'Laugh through pain, gallows humor, absurd cruelty, or jokes that land like punches.',
	'Satire'                   => 'Skewer power, mirror society, exaggerate until truth shows through, or laugh before you flinch.',
	'Parody'                   => 'Quote classics, twist tropes, wink at fans, or genres lovingly torn apart and taped back wrong.',
	'Buddy Comedy'             => 'Mismatched partners, road-trip bickering, loyalty through chaos, or friendship forged in bad ideas.',
	'Based on Children\'s book' => 'Beloved pages brought to screen, wonder preserved, lessons for all ages, or nostalgia walking into daylight.',
	'Feel Good'                => 'Warm endings, small victories, kindness rewarded, or stories that leave the room a little lighter.',
	'Holiday'                  => 'Traditions, crowded tables, seasonal magic, or celebrations that hide and heal old wounds.',
	'Christmas'                => 'Snow-lit streets, gifts with meaning, family friction, or miracles dressed as ordinary kindness.',
	'Summer'                   => 'Heat haze, freedom, first jobs, or nights too long to waste on sleep.',
	'Road Trip'                => 'Highways, detours, playlists, or miles that change whoever sits behind the wheel.',
	'Adventure'                => 'Maps, danger, wonder, or journeys where the destination matters less than who you become en route.',
	'Treasure Hunt'            => 'Clues on old parchment, rival seekers, traps, or fortune that tests every bond in the crew.',
	'Western'                  => 'Dusty towns, standoffs at saloons, frontier justice, or riders who chase horizons and vendettas alike.',
	'Marvel Cinematic Universe (mcu)' => 'Shared heroes, cosmic stakes, crossovers and callbacks, or one universe stitched from a thousand frames.',
	'Based on novel or book'   => 'Pages lifted to screen, prose bent into performance, faithful readers, or new takes on famous chapters.',
	'Underdog'                 => 'Counted out, underestimated, last chances, or victories snatched from giants who never saw you coming.',
	'Medieval'                 => 'Castles, crowns, swords and fealty, or ages before gunpowder when honor was written in steel.',
	'Second Chance'            => 'Redemption arcs, parole and penance, forgiveness earned, or mistakes that refuse to stay buried.',
	'Fish Out of Water'        => 'Wrong rooms, wrong rules, comic friction, or adaptation that rewrites who you thought you were.',
	'Soldier'                  => 'Chain of command, brotherhood under fire, orders versus conscience, or duty that redefines sacrifice.',
	'Martial Arts'             => 'Discipline, dojos, honor duels, or fists that speak when words run out.',
	'Superhero'                => 'Secret identities, city-saving stakes, moral capes, or power that demands impossible choices.',
	'Vigilante'                => 'Masks outside the law, moral gray zones, city streets as jury, or justice with a body count.',
	'War'                      => 'Front lines, home fronts, collateral hearts, or peace that arrives too late for too many.',
	'Military'                 => 'Chain of command, brotherhood under fire, orders versus conscience, or duty that redefines sacrifice.',
	'Special Forces'           => 'Black ops, impossible odds, silent insertions, or missions the world will never read about.',
	'Rescue Mission'           => 'Clocks ticking, hostages waiting, extraction plans, or heroes who run toward the gunfire.',
	'Based on Comic'           => 'Panels leaping to life, ink becoming motion, origin stories, or capes and shadows from serial page turns.',
	'Disaster'                 => 'Crumbling infrastructure, crowds panicking, ordinary people heroic, or survival measured in minutes.',
	'Natural Disaster'         => 'Storms, quakes, walls of water, or nature reminding cities how small blueprints really are.',
	'Based on video game.'     => 'Controllers become cinema, pixels with lore, quests adapted, or players who already know every corridor.',
	'Exorcism'                 => 'Holy rites, rotating heads, faith versus evil, or voices that refuse to leave the host.',
	'Chase'                    => 'Footsteps echoing, alleys narrowing, breath loud, or fugitives who cannot stop running.',
	'Manhunt'                  => 'Trackers, dogs, grids tightening, or hiding in plain sight while the net slowly drops.',
	'Battle'                   => 'Formations, charges, mud and metal, or victory written in who still stands when smoke clears.',
	'Sword Fighting'           => 'Blades singing, honor codes, duels at dawn, or steel that decides arguments words cannot.',
	'Gladiator'                => 'Sand arenas, roaring crowds, chains and glory, or freedom bought with blood for entertainment.',
	'Vikings (norsemen)'       => 'Longships, coastal raids, sagas, or warriors who believe Valhalla waits past the next wave.',
	'Pirate'                   => 'Black flags, buried chests, mutiny and rum, or freedom on a sea that owes no king.',
	'Time Loop'                => 'Relive the same day, learn patterns, break cycles, or decay while the calendar refuses to turn.',
	'Criminal'                 => 'Heists, syndicates, moral gray, or lawbreakers who become the story’s dark gravity.',
	'Mind Control'             => 'Puppets on strings, whispered commands, stolen wills, or fighting thoughts that are not your own.',
	'Memory Loss'              => 'Blank journals, strangers who claim love, clues on photographs, or identity rebuilt from shards.',
	'Amnesia'                  => 'Wake unknown, trust carefully, rediscover crimes, or past selves that knock like strangers.',
	'Dream'                    => 'Surreal logic, shifting rooms, symbols that sting, or waking that feels like the real trap.',
	'Cult'                     => 'Charismatic leaders, closed circles, belief pushed to extremes, or paradise that tastes like poison.',
	'DC Universe (dcu)'        => 'Gotham grit, capes and icons, parallel earths, or heroes who wrestle gods and their own shadows.',
	'World War ii'             => 'Front lines, air raids, resistance cells, or ordinary souls caught in history’s largest storm.',
	'Dramatic'                 => 'High stakes, fractured families, emotional crescendos, or stories that live on the edge of a tear.',
	'Doppelgänger'             => 'Doubles in windows, stolen lives, who-was-first dread, or evil twins without the cartoon wink.',
	'Clone'                    => 'Copies with rights, labs and ethics, originals versus shadows, or humanity measured in uniqueness.',
	'Experiment'               => 'Hypotheses on people, sterile rooms, breakthroughs and screams, or science without off switches.',
	'Scientist'                => 'Breakthrough obsession, ethics stretched, discovery at midnight, or hubris dressed in lab coats.',
	'Drugs'                    => 'Cartels, corridors of addiction, moral decay, or highs that bankrupt body and soul.',
	'Hacker'                   => 'Keyboard duels, firewalls falling, anonymity, or digital ghosts who reshape reality from basements.',
	'Technology'               => 'Innovation racing ahead, unintended costs, connected lives, or tools that outgrow their makers.',
	'Future'                   => 'Tomorrow’s skylines, new customs, hope and warning, or worlds built on today’s choices extrapolated.',
	'Space Colony'             => 'Domed habitats, resource rationing, pioneers off-world, or humanity’s first fragile foothold beyond Earth.',
	'Alien Planet'             => 'Explore new fantastic worlds, discover exotic lifeforms, adapt to hostile environments, or encounter dangerous, unknown creatures.',
);

foreach ( $trends_popular_keyword_labels as $trends_popular_label ) {
	$term = get_term_by( 'name', $trends_popular_label, 'mv_keyword' );
	if ( ! $term || is_wp_error( $term ) ) {
		$term = get_term_by( 'slug', sanitize_title( $trends_popular_label ), 'mv_keyword' );
	}
	if ( ! $term || is_wp_error( $term ) ) {
		$trends_popular_keywords_not_found[] = $trends_popular_label;
		continue;
	}

	if ( '' !== trim( (string) $term->description ) ) {
		continue;
	}

	if ( ! current_user_can( 'edit_term', (int) $term->term_id ) ) {
		continue;
	}

	$part1 = str_replace( '%term%', $term->name, $trends_keyword_description_part1_template );

	if ( isset( $trends_popular_keyword_part2_by_label[ $trends_popular_label ] ) ) {
		$part2 = $trends_popular_keyword_part2_by_label[ $trends_popular_label ];
	} else {
		$part2 = sprintf(

			__( 'Explore movies and TV series that revolve around %s—stories, characters, and worlds fans of the theme will love.', 'blockter' ),
			$term->name
		);
	}

	$full = trim( $part2 ) . ' -- ' . trim( $part1 );

	wp_update_term( (int) $term->term_id, 'mv_keyword', array( 'description' => $full ) );
}
*/

/**
 * Create mv_keyword parent “theme” terms (if missing) and assign child keywords.
 * Grouping matches the site keyword list used on this template; descriptions match the 10 theme blurbs.
 * Runs only for administrators to avoid work on anonymous page loads.
 */
/*
if ( taxonomy_exists( 'mv_keyword' ) && current_user_can( 'manage_options' ) ) {
	$blockter_mv_keyword_theme_parents = array(
		'theme-core-trending' => array(
			'name'        => 'Core / Trending Themes',
			'description' => 'Explore the most popular movie and TV themes trending right now. From time travel and dystopian futures to artificial intelligence and survival stories, discover what audiences are watching and talking about.',
			'children'    => array(
				'Time Travel',
				'Zombie Apocalypse',
				'Alien Invasion',
				'Post-Apocalyptic',
				'Survival',
				'Artificial Intelligence (a.i.)',
				'Dystopia',
				'Space Exploration',
				'Parallel World',
				'alternate timeline',
				'Future',
				'Technology',
			),
		),
		'theme-franchises-universes' => array(
			'name'        => 'Franchises & Universes',
			'description' => 'Dive into the biggest movie and TV franchises, from superhero universes to iconic cinematic sagas. Explore collections like Marvel and DC, and discover how your favourite stories connect across films and series.',
			'children'    => array(
				'Marvel Cinematic Universe (mcu)',
				'DC Universe (dcu)',
				'Superhero',
				'Based on Comic',
				'Based on video game.',
				'Based on novel or book',
				'Based on Children\'s book',
			),
		),
		'theme-supernatural-horror' => array(
			'name'        => 'Supernatural & Horror',
			'description' => 'Enter a world of supernatural and horror stories, from ghosts and demons to vampires, witches, and haunted houses. Discover chilling movies and TV shows packed with fear, mystery, and the unknown.',
			'children'    => array(
				'Supernatural',
				'Ghost',
				'Demon',
				'Vampire',
				'Werewolf',
				'Witchcraft',
				'Haunted House',
				'Monster',
				'Creature Feature',
				'Exorcism',
				'Cult',
			),
		),
		'theme-scifi-mindbending' => array(
			'name'        => 'Sci-Fi & Mind-Bending',
			'description' => 'Explore mind-bending sci-fi movies and TV shows featuring time loops, virtual reality, simulations, and alternate realities. Perfect for fans of complex stories that challenge perception and reality.',
			'children'    => array(
				'Time Loop',
				'Mind Control',
				'Memory Loss',
				'Amnesia',
				'Dream',
				'Doppelgänger',
				'Clone',
				'Experiment',
				'Scientist',
				'Hacker',
				'Alien Planet',
				'Space Colony',
			),
		),
		'theme-action-crime' => array(
			'name'        => 'Action & Crime',
			'description' => 'Discover action-packed movies and crime-driven stories, from heists and assassins to organised crime and political thrillers. Explore high-stakes worlds full of danger, strategy, and suspense.',
			'children'    => array(
				'Serial Killer',
				'Heist',
				'Revenge',
				'Prison Escape',
				'Bank Robbery',
				'Spy',
				'Espionage',
				'Assassin',
				'Hitman',
				'Police Investigation',
				'Detective',
				'Murder Mystery',
				'true Crime',
				'Psychological Thriller',
				'Courtroom Drama',
				'Political Thriller',
				'Conspiracy',
				'Corruption',
				'Gangster',
				'Mafia',
				'Mystery',
				'Crime Investigation',
				'Vigilante',
				'Criminal',
				'Chase',
				'Manhunt',
				'Battle',
				'Martial Arts',
				'Drugs',
			),
		),
		'theme-war-history-realism' => array(
			'name'        => 'War, History & Realism',
			'description' => 'Explore powerful stories based on real events, wars, and historical moments. From World War dramas to true stories and military action, discover films and series grounded in reality.',
			'children'    => array(
				'Based on True Story',
				'World War ii',
				'War',
				'Military',
				'Special Forces',
				'Rescue Mission',
				'Soldier',
				'Disaster',
				'Natural Disaster',
			),
		),
		'theme-historical-adventure' => array(
			'name'        => 'Historical & Adventure',
			'description' => 'Step into epic adventures and historical worlds, from medieval battles and pirates to westerns and treasure hunts. Discover stories filled with exploration, action, and legendary journeys.',
			'children'    => array(
				'Western',
				'Medieval',
				'Gladiator',
				'Vikings (norsemen)',
				'Pirate',
				'Treasure Hunt',
				'Adventure',
				'Road Trip',
				'Underdog',
				'Sword Fighting',
			),
		),
		'theme-tv-drama' => array(
			'name'        => 'TV & Drama',
			'description' => 'Explore gripping TV dramas and character-driven stories, from family and teen drama to crime, mystery, and workplace series. Perfect for binge-worthy shows full of emotion and storytelling.',
			'children'    => array(
				'Teen Drama',
				'High School',
				'Coming of Age',
				'Family Drama',
				'Romance',
				'Love Triangle',
				'Friendship',
				'Small Town',
				'Police Drama',
				'Medical Drama',
				'legal thriller',
				'Workplace',
				'Reality TV',
				'Dramatic',
			),
		),
		'theme-feelgood-light' => array(
			'name'        => 'Feel-Good & Light',
			'description' => 'Looking for something uplifting? Discover feel-good movies and TV shows, from romantic stories and comedies to holiday favourites and light-hearted adventures.',
			'children'    => array(
				'Romantic',
				'Dark Comedy',
				'Satire',
				'Parody',
				'Buddy Comedy',
				'Feel Good',
				'Holiday',
				'Christmas',
				'Summer',
				'Second Chance',
			),
		),
		'theme-additional-niche' => array(
			'name'        => 'Additional / Niche',
			'description' => 'Explore unique and niche movie and TV themes, from reality TV and psychological topics to stories centred around real-life struggles and unconventional subjects.',
			'children'    => array(
				'Dark Secret',
				'Hidden Identity',
				'Double Life',
				'Post-Traumatic Stress disorder (ptsd)',
				'Fish Out of Water',
			),
		),
	);

	$blockter_theme_parent_slugs = array_keys( $blockter_mv_keyword_theme_parents );

	foreach ( $blockter_mv_keyword_theme_parents as $parent_slug => $cfg ) {
		$parent_term = get_term_by( 'slug', $parent_slug, 'mv_keyword' );
		if ( ! $parent_term || is_wp_error( $parent_term ) ) {
			$inserted = wp_insert_term(
				$cfg['name'],
				'mv_keyword',
				array(
					'slug'        => $parent_slug,
					'description' => $cfg['description'],
					'parent'      => 0,
				)
			);
			if ( is_wp_error( $inserted ) ) {
				continue;
			}
			$parent_id = (int) $inserted['term_id'];
		} else {
			$parent_id = (int) $parent_term->term_id;
		}

		foreach ( $cfg['children'] as $child_name ) {
			$child = get_term_by( 'name', $child_name, 'mv_keyword' );
			if ( ! $child || is_wp_error( $child ) ) {
				$child = get_term_by( 'slug', sanitize_title( $child_name ), 'mv_keyword' );
			}
			if ( ! $child || is_wp_error( $child ) ) {
				continue;
			}
			if ( in_array( $child->slug, $blockter_theme_parent_slugs, true ) ) {
				continue;
			}
			if ( (int) $child->term_id === $parent_id ) {
				continue;
			}
			if ( (int) $child->parent === $parent_id ) {
				continue;
			}
			wp_update_term(
				(int) $child->term_id,
				'mv_keyword',
				array( 'parent' => $parent_id )
			);
		}
	}
}
*/
get_header();

$trends_mv_trending_view_all_urls = array();
if ( taxonomy_exists( 'mv_trending' ) ) {
    foreach ( array( 'movie-day', 'movie-week', 'tv-day', 'tv-week' ) as $trends_t_slug ) {
        $trends_mv_term = get_term_by( 'slug', $trends_t_slug, 'mv_trending' );
        if ( $trends_mv_term && ! is_wp_error( $trends_mv_term ) ) {
            $trends_mv_link = get_term_link( $trends_mv_term );
            $trends_mv_trending_view_all_urls[ $trends_t_slug ] = is_wp_error( $trends_mv_link ) ? '' : $trends_mv_link;
        } else {
            $trends_mv_trending_view_all_urls[ $trends_t_slug ] = '';
        }
    }
}

// Emoji titles for trending sliders (same character set as keyword theme groups).
$blockter_trends_slider_title_emojis = array(
	'movies' => '🎬',
	'week'   => '🔥',
	'tv'     => '📺',
);
?>

<div class="collections-page">
    <?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<h2 class="trends-trending-title"><?php esc_html_e( 'Themes', 'blockter' ); ?></h2>
				<p class="collection-description-text" itemprop="description"><?php esc_html_e( 'Explore movies and TV shows by theme, story, and style. From time travel and crime thrillers to supernatural worlds and epic adventures, find exactly what you’re in the mood for.', 'blockter' ); ?></p>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row trends-page-search-row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<?php echo blockter_top_ajaxsearch_form( 'searchmovie-trends', 'trends-page-search' ); ?>
			</div>
		</div>
		<?php
		$blockter_trends_mv_keyword_parent_slugs_ordered = array(
			'theme-core-trending',
			'theme-franchises-universes',
			'theme-supernatural-horror',
			'theme-scifi-mindbending',
			'theme-action-crime',
			'theme-war-history-realism',
			'theme-historical-adventure',
			'theme-tv-drama',
			'theme-feelgood-light',
			'theme-additional-niche',
		);
		$blockter_trends_mv_keyword_parent_emojis = array(
			'theme-core-trending'        => '🔥',
			'theme-franchises-universes' => '🎬',
			'theme-supernatural-horror'  => '👻',
			'theme-scifi-mindbending'    => '🧠',
			'theme-action-crime'         => '⚔️',
			'theme-war-history-realism'  => '🌍',
			'theme-historical-adventure' => '🏰',
			'theme-tv-drama'             => '📺',
			'theme-feelgood-light'       => '😊',
			'theme-additional-niche'     => '🎯',
		);

		$keywords = get_terms(
			array(
				'taxonomy'   => 'mv_keyword',
				'hide_empty' => true,
			)
		);

		if ( ! is_wp_error( $keywords ) && ! empty( $keywords ) ) {
			$keywords = array_values(
				array_filter(
					$keywords,
					function ( $keyword ) {
						return '' !== trim( (string) $keyword->description );
					}
				)
			);
		} else {
			$keywords = array();
		}

		$theme_parent_ids = array();
		foreach ( $blockter_trends_mv_keyword_parent_slugs_ordered as $p_slug ) {
			$p_term = get_term_by( 'slug', $p_slug, 'mv_keyword' );
			if ( $p_term && ! is_wp_error( $p_term ) ) {
				$theme_parent_ids[ $p_slug ] = (int) $p_term->term_id;
			}
		}
		$valid_parent_ids = array_values( $theme_parent_ids );

		$children_by_parent = array();
		$orphan_keywords    = array();
		foreach ( $keywords as $keyword ) {
			if ( in_array( $keyword->slug, $blockter_trends_mv_keyword_parent_slugs_ordered, true ) ) {
				continue;
			}
			$parent_id = (int) $keyword->parent;
			if ( $parent_id && in_array( $parent_id, $valid_parent_ids, true ) ) {
				if ( ! isset( $children_by_parent[ $parent_id ] ) ) {
					$children_by_parent[ $parent_id ] = array();
				}
				$children_by_parent[ $parent_id ][] = $keyword;
			} else {
				$orphan_keywords[] = $keyword;
			}
		}
		foreach ( $children_by_parent as &$child_list ) {
			usort(
				$child_list,
				function ( $a, $b ) {
					return strcasecmp( $a->name, $b->name );
				}
			);
		}
		unset( $child_list );
		usort(
			$orphan_keywords,
			function ( $a, $b ) {
				return strcasecmp( $a->name, $b->name );
			}
		);

		$trends_collections_has_items = false;
		foreach ( $blockter_trends_mv_keyword_parent_slugs_ordered as $p_slug ) {
			if ( ! isset( $theme_parent_ids[ $p_slug ] ) ) {
				continue;
			}
			$pid = $theme_parent_ids[ $p_slug ];
			if ( ! empty( $children_by_parent[ $pid ] ) ) {
				$trends_collections_has_items = true;
				break;
			}
		}
		if ( ! $trends_collections_has_items && ! empty( $orphan_keywords ) ) {
			$trends_collections_has_items = true;
		}
		?>
		<div class="trends-keyword-collections-root">
		<?php if ( $trends_collections_has_items ) : ?>
			<?php
			foreach ( $blockter_trends_mv_keyword_parent_slugs_ordered as $p_slug ) :
				if ( ! isset( $theme_parent_ids[ $p_slug ] ) ) {
					continue;
				}
				$parent_term = get_term( $theme_parent_ids[ $p_slug ], 'mv_keyword' );
				if ( ! $parent_term || is_wp_error( $parent_term ) ) {
					continue;
				}
				$child_terms = isset( $children_by_parent[ (int) $parent_term->term_id ] )
					? $children_by_parent[ (int) $parent_term->term_id ]
					: array();
				if ( empty( $child_terms ) ) {
					continue;
				}
				$emoji = isset( $blockter_trends_mv_keyword_parent_emojis[ $p_slug ] )
					? $blockter_trends_mv_keyword_parent_emojis[ $p_slug ]
					: '';
				?>
			<section class="trends-keyword-theme-group" id="<?php echo esc_attr( $p_slug ); ?>">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<h2 class="trends-trending-title trends-trending-title--with-icon">
							<?php if ( '' !== $emoji ) : ?>
							<span class="trends-trending-title-icon trends-trending-title-icon--emoji" aria-hidden="true"><?php echo esc_html( $emoji ); ?></span>
							<?php endif; ?>
							<span class="trends-trending-title-text"><?php echo esc_html( $parent_term->name ); ?></span>
						</h2>
						<?php if ( '' !== trim( (string) $parent_term->description ) ) : ?>
						<p class="collection-description-text trends-trending-intro" itemprop="description"><?php echo esc_html( wp_strip_all_tags( $parent_term->description ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>
				<div class="collections-list collections-list--theme-group">
				<?php
				foreach ( $child_terms as $keyword ) :
					$image_id  = get_term_meta( $keyword->term_id, 'thumbnail_id', true );
					$image_url = wp_get_attachment_image_src( $image_id, 'full' );
					?>
					<div class="collection-item">
						<a href="<?php echo esc_url( get_term_link( $keyword ) ); ?>">
							<?php if ( $image_url ) : ?>
							<img src="<?php echo esc_url( $image_url[0] ); ?>" alt="<?php echo esc_attr( $keyword->name ); ?>" />
							<?php endif; ?>
							<div class="collection-content">
								<h2><?php echo esc_html( $keyword->name ); ?></h2>
								<p><?php
									$trends_desc_raw   = (string) $keyword->description;
									$trends_desc_parts = explode( ' -- ', $trends_desc_raw, 2 );
									echo esc_html( trim( $trends_desc_parts[0] ) );
								?></p>
							</div>
						</a>
					</div>
				<?php endforeach; ?>
				</div>
			</section>
				<?php
			endforeach;

			if ( ! empty( $orphan_keywords ) ) :
				?>
			<section class="trends-keyword-theme-group trends-keyword-theme-group--other" id="trends-keywords-other">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<h2 class="trends-trending-title trends-trending-title--with-icon">
							<span class="trends-trending-title-icon trends-trending-title-icon--emoji" aria-hidden="true">📌</span>
							<span class="trends-trending-title-text"><?php esc_html_e( 'Other keywords', 'blockter' ); ?></span>
						</h2>
						<p class="collection-description-text trends-trending-intro"><?php esc_html_e( 'Keywords not yet assigned to a theme group.', 'blockter' ); ?></p>
					</div>
				</div>
				<div class="collections-list collections-list--theme-group">
				<?php
				foreach ( $orphan_keywords as $keyword ) :
					$image_id  = get_term_meta( $keyword->term_id, 'thumbnail_id', true );
					$image_url = wp_get_attachment_image_src( $image_id, 'full' );
					?>
					<div class="collection-item">
						<a href="<?php echo esc_url( get_term_link( $keyword ) ); ?>">
							<?php if ( $image_url ) : ?>
							<img src="<?php echo esc_url( $image_url[0] ); ?>" alt="<?php echo esc_attr( $keyword->name ); ?>" />
							<?php endif; ?>
							<div class="collection-content">
								<h2><?php echo esc_html( $keyword->name ); ?></h2>
								<p><?php
									$trends_desc_raw   = (string) $keyword->description;
									$trends_desc_parts = explode( ' -- ', $trends_desc_raw, 2 );
									echo esc_html( trim( $trends_desc_parts[0] ) );
								?></p>
							</div>
						</a>
					</div>
				<?php endforeach; ?>
				</div>
			</section>
				<?php
			endif;
			?>
		<?php else : ?>
			<p class="trends-keywords-empty"><?php esc_html_e( 'No Trends available.', 'blockter' ); ?></p>
		<?php endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
