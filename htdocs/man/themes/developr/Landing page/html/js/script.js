/**
 *
 * '||''|.                            '||
 *  ||   ||    ....  .... ...   ....   ||    ...   ... ...  ... ..
 *  ||    || .|...||  '|.  |  .|...||  ||  .|  '|.  ||'  ||  ||' ''
 *  ||    || ||        '|.|   ||       ||  ||   ||  ||    |  ||
 * .||...|'   '|...'    '|     '|...' .||.  '|..|'  ||...'  .||.
 *                                                  ||
 * --------------- By Display:inline ------------- '''' -----------
 *
 * Landing page setup
 *
 * Structural good practices from the article from Addy Osmani 'Essential jQuery plugin patterns'
 * @url http://coding.smashingmagazine.com/2011/10/11/essential-jquery-plugin-patterns/
 */

/*
 * The semi-colon before the function invocation is a safety
 * net against concatenated scripts and/or other plugins
 * that are not closed properly.
 */
;(function($, window, document, undefined)
{
	/*
	 * undefined is used here as the undefined global variable in ECMAScript 3 and 4 is mutable (i.e. it can
	 * be changed by someone else). undefined isn't really being passed in so we can ensure that its value is
	 * truly undefined. In ES5, undefined can no longer be modified.
	 */

	/*
	 * window and document are passed through as local variables rather than as globals, because this (slightly)
	 * quickens the resolution process and can be more efficiently minified.
	 */

		// Objects cache
	var win = $(window),
		doc = $(document),
		bod = $(document.body),

		// Devices
		smartphone = $('#smartphone'),
		tablet = $('#tablet'),
		desktop = $('#desktop')

		// Initial position
		devicesPos = {
			smartphone:	parseInt(smartphone.css('left'), 10),
			tablet:		parseInt(tablet.css('left'), 10),
			desktop:	parseInt(desktop.css('left'), 10)
		}

		// Maximum move
		devicesMoves = {
			smartphone:	100,
			tablet:		68,
			desktop:	40
		},

		// Term
		term = $('.term'),
		currentTerm = 0;

	// Devices parallax on mouse move
	doc.on('mousemove', function(event)
	{
			// Screen width
		var width = win.width(),

			// Position relative to screen center
			pos = (event.pageX-(width/2))/width;

		// Set positions
		smartphone.css('left', Math.round(devicesPos.smartphone+(devicesMoves.smartphone*pos))+'px');
		tablet.css('left', Math.round(devicesPos.tablet+(devicesMoves.tablet*pos))+'px');
		desktop.css('left', Math.round(devicesPos.desktop+(devicesMoves.desktop*pos))+'px');
	});

	// Smooth scrolling
	doc.on('click', 'a', function(event)
	{
		var link = $(this).attr('href'),
			target;

		// Only for hashtags
		if (link.indexOf('#') < 0)
		{
			return;
		}

		// Target
		target = $('#'+link.split('#')[1]);
		if (target.length === 0)
		{
			return;
		}

		// Stop normal scroll
		event.preventDefault();

		// Scroll
		$('html, body').animate({
			scrollTop: target.offset().top
		});
	});

	/*
	 * Terms scrolling
	 */

	// Erase existing term
	function eraseTerm()
	{
		var text = term.text();
		if (text.length > 1)
		{
			// Trim
			term.text(text.substr(0, text.length-1));
			setTimeout(eraseTerm, 80);
		}
		else
		{
			// Empty
			term.text('');

			// Next term
			currentTerm = (currentTerm === terms.length-1) ? 0 : currentTerm+1;
			setTimeout(writeTerm, 80);
		}
	};

	// Write new term
	function writeTerm()
	{
		var text = term.text();
		if (text.length < terms[currentTerm].length)
		{
			// Add
			term.text(terms[currentTerm].substr(0, text.length+1));
			setTimeout(writeTerm, 80);
		}
		else
		{
			// Next round
			setTimeout(eraseTerm, 3000);
		}
	};

	// Start
	setTimeout(eraseTerm, 3000);

})(this.jQuery, window, document);