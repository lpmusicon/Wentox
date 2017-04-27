<?php
$topHtml = '
<!doctype html>
<html>
    <head>
    <!-- Created by Rafał Schmidt and Grzegorz Mrózek -->
       	<meta charset="utf-8" />
       	<meta name="language" content="pl" />
       	<title>Wentox - twórz quizy łatwiej</title>
       	<meta name="author" content="Grzegorz Mrózek & Rafał Schmidt" />
        <link href=\'http://fonts.googleapis.com/css?family=Open+Sans:700,400,300\' rel=\'stylesheet\' type=\'text/css\'>
        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <style>
        * {
        	margin: 0;
        	padding: 0;
        	border: 0;
        	font-size: 100%;
        	font: inherit;
        	vertical-align: baseline;
            text-decoration:none;
        }
        /* HTML5 display-role reset for older browsers */
        article, footer, header, section { display: block; }
        body {
            line-height: 1;
        	font-family: "Open Sans", sans-serif;
            font-weight: 300;
        	background-color:#fff;
        }
        input{ outline: none; }
        #timer {
            position: fixed;
            top: 10px;
            left: 10px;
            background: #4D91D0;
            font: 24px "Open Sans";
            color: #fff;
            padding: 10px;
			border: 1px solid white;
        }
        #topic {
            text-align: center;
            padding: 15px 0;
            font: 18px "Open Sans";
        }
        section {
             margin: 10px 5px;
        }
        .question {
            margin: 10px 0;
            font: 20px "Open Sans";
            text-transform: uppercase;
        }
        label {
            display: block;
            margin: 8px 0px;
            font-size: 16px;
            text-transform: capitalize;
        }
        input {
            width: 16px;
            height: 16px;
            margin: 0 10px -2px 0;
        }
        #end {
            margin: 20px 0;
            text-align: center;
            background-color:#444;
        	padding: 12px 0 0;
        	height: 28px;
        	color:#fff;
        	cursor: pointer;
        }
        #end:hover {
            background: #222;
        }    
        .pagewidth {
        	margin: 0 auto;
        	width: 550px;	
        }
        header{
        	background-color: #4D91D0;
        	height: 150px;
        	width: 100%;
        }    
        header #logo{
        	padding: 20px 0 0;
        	margin: 0 auto;
        	display: block;
        }
        footer {	
        	text-align:center;
        	margin: 15px auto 15px !important; 
        	font-size: 14px;
        } 
		#score{
			text-align: center;
			position: absolute;
			top: 180px;
			-webkit-box-shadow: 0px 0px 2000px 2000px rgba(0,0,0,0.4)!important;
			-moz-box-shadow: 0px 0px 2000px 2000px rgba(0,0,0,0.4)!important;
			box-shadow: 0px 0px 2000px 2000px rgba(0,0,0,0.4)!important;
			padding: 40px 20px;
			margin: 20px 0 0 0;
			background-color: #4D91D0;
			color: white;
			left: 50%;
			margin-left: -295px;
			display: none;
			margin-bottom: 30px;
		}
		#score span,
		#score h3{
			line-height: 28px;
			font-weight: bold;
		}
		#score h3,
		#showAnswers{
			padding: 2px;
			background: white;
			color: #4D91D0;
			font-weight: 600;
		}
		
		#showAnswers{
			margin-top: 5px;
			padding: 5px 2px;
		}
		#showAnswers{ cursor: pointer; }
		#yourAnswers{ display: none; }
		#score h4{
			line-height: 22px;
			padding: 3px 0;
		}
		#score h4.yes{ font-weight: 700; }
		#score h4.yes::after{ content: " - poprawna" }
    </style>
</head>
<body>
	<header>
    <div class="pagewidth">
<img id="logo" alt="Wentox, twórz quizy łatwiej" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAVAAAABiCAYAAAAGLYvLAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDIxIDc5LjE1NTc3MiwgMjAxNC8wMS8xMy0xOTo0NDowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6N0IxNjZERUI2NzU5MTFFNEEzNzhBMjYyMEU1M0Q3QUYiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6N0IxNjZERUE2NzU5MTFFNEEzNzhBMjYyMEU1M0Q3QUYiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoyOThDNTU0NjY3NTkxMUU0OUY0RjkyN0ExMDI2RkZBRiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoyOThDNTU0NzY3NTkxMUU0OUY0RjkyN0ExMDI2RkZBRiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgDffOMAABuLSURBVHja7F0NlGRFdb47bGBZfvah/InANiBRg2QbJGAO4PQKIiKwvTn8Q9heD2iQwM7kKBKj6RlBiajpnahBQ7BnAcX4k5mJIBII06sQTzA4M5qACDLNj/zDDPIPWTp1efedqamu91699+p1vzdzv3PumenXr+tV1av66t6qW7eWtFotWKTYQcg+QgrSX7x2v5D7SO4S8jIwGAyGBksXWXlXCjlfyNlCdjO4/zEhlwi5Ushr3FwYDIaMJYtEAz1cSL+QspCtYvwetdJzhdzKTYbBYCw2Ar1ZyNEJ0/i9kEOF3MPNhsFgIHoWSTknLaSxo5Ax+stgMBiLhkCnLKXzdiEXcrNhMBisgcbD4dxsGAwGYrHMgaK3wfNCthHyrJBbwF0YmhbygJA3CTkM3IWibULSmqX7W9x8GAwm0MVS1m8JuV3I1USmOpwm5DqDtPYU8jtuPgwGEyjXwhyWCXkcgheK0LF+OyGvc3UxGIsbPVwFbeT4eMg9dzN5MhgMJtB24JbO/UPu+SVXE4PBYAKdjyVCrjC47x+5qhgMBhPofHxayAdC7rleyB1cVQwG4w2tixeR3sB5BprlK+Bu5WQTnsFgZF4DxdBy23XgOacL+WrIPbhodCaTJ4PBkNGtcHbbCjmCNLpdhexCsrP013NoxyAejwp5hORR5S86xD8cMx/HCtlkMJDg9s0fcHNhMBjdMOGXElkeRfKnQra2mP694EZcQhkHd7dRGPYVcqcQJ+S+y4R8ipsKg8HoJIGiVrdWyIeFvFfI9h0q0/8JuQHcIMg/FrJFcw9qt7gr6d0haaF2WuFmwmAwOkWgfyDkLCGfBDd6UTeB2y2/IuQLynVcMDov5Lc3CTmeCJnBYDC0prUtLBdyjpCPC9krI+V7q5A3K9dONyDP3wg5lcmTwWCkrYGiAzoel/HX4C7+ZAnoeoS7ix6lzzjvORUynYCLVhiZ6dfcPBgMRpoaKK6gXyvk/Rkt3zUSeSLODSFPdFc6g8mTwWCkTaDvAzdE3O4ZLp/qenRqyP246HQDNwsGg2GCOI70eKrlZ8F1GcoyeeIxxD+VPh9G5nwQruYmwWAw0tJA0WT/HrhuSVnHfwl5Qfp8esj96Ds6xk2CwWCkQaDLybw9JCdlUw+SOzHk/hFw44EyGAyGVQJFs/07OSJPxCNKOVF7fiXg/gY3BwaDkQaBDgk5IWdlk/fHoz/n9vy6GQyGTZgsIqFj/Pk5LNvDCX77Dm4aDAYjKYGeLOTynJbt/gS//byQD3HzsI4+cIO9lDv8XAwYU5IkS6iAO/8+sgjef1F5DwWLaWNaM0Im6Dlp5R/Tn/aeEbQTCbdB4pbG5Tl8Ud8GN36njD8DdyUe3ZkwmMgkuEd4jCr3nQLuXnmcLz0A3HPgGXaIoi59RpeypuVneOTYSx2qQA1djbjVELI6A3VSo0HFA+7o29jhgaUokcMK5ftnqZ9MxuwHDr33dSGkNknvZBP9Hwcj0sCM7eogy323SIO/15Yw7X2CCPSbQtbnsKOi6xIGMfHObV9GhOhXFgymfIH0+Wx6kYjhnNRBkcgJX+rajJJ+VciA9Hk1BC/c+WmKvZrOH0erPMiws2Kn3ED/D2kG3CSYUch9lN6fTa2sQM9YJdWZE0NLC3tfKnFWlcHBFPiMQYi+qKsS2QClYwNl6l/qQOxLoAdS48rjmUmXCvmM9PlzEB7PE2OU3ioRrnw2PEZkyvLuJIdMCifjpD+uEF1Qh0zTDIuiAZco31F/F7fTq5qxR4C9hpp32OCTBKbk7kc2UTFMGrmpMjCtTAnM0rtKqkyomqcH7GPDfqvwX4T8Hji3Wfr/D4V8wuA350oEir6gd5Opj/i6kP2EvJph7dNRTOXBFMxj22iGaE5pYr1h/fT6kNVwB+pER95ZRy1A6xymvtlU6tebF3U0Uz5FGlBmDd/puEYL7k+LPPEfHYFiYJAPQH4hn1t0DLjxScOwSvl8n0Sge4I7L3ptjogozU4eF6UIBDoK0QNZN6U0vf+TzuH5kWpadTsVQt420FDq6oGAd4ED2Ur6P2yXXt3nnW2kAX3WJy8e2ZWJgB0NgZmQaIPajbxA2UfTLnGUCR15elNkc5YTmvCS9AiZbOUXjynl+SfD392j/O5byvd3KN9nTaaV/NYzlr+Ckr+JkPsdITOa91QVUqLvO5HvqiYP0ynVSYuued+XIrZ9zNc4yYTm+74U66nuk59ixHQcn7TGI/x+JuZvZSlq0pnRlUfVQE/VaGN5wushn/2gLiYcrHz+E3DPcfpZRss9qphOxYzlrxBS3ypm6Z6SRpPp9PyyrixFiL9a7FcnTUVTatAcHmpU8ur4s9KzmwHaVUtJO63VfZ3mORnB9Fbf+3rJhJetl4qB5u9piOPKb/silN/RzOHOUnna3rlKoKdAvvEWcLdsPkGff2X4u5uk/7ejuVMVF2aYQMcyTqC9AfPUUcrYjfllHdZZIFDHx5wFC8RXiDBdkgRVi+SpzjGWlHKsMZw6aVCd9Sn5HDWoB4fIt2hCngh5oWgZZDcwchQcJP1/nZAnQ+7fDPP9Ew8C/QLaSeD6xmYRjQidvxsoGuQ3jAS64ZpV8LlegeSrzKsC5j87rfHHndMe0BD1akvvaihkDj0I/UqZ8V2ZbFSIRJ4qga4m7SvvOE76/xkazZ7zufcWcCPQe+YOHk9yqc+9qK2fnOFyNww7vymQJGaUwSVJZwMDs7PTJBCXQL1Fj7Q1UFv5tj34OJp2YdsHuaF5ZpRBa72SlyJpokFTEZHIUyXQvAUL8QPu2z9U+ow+nOjX+g9CbgP3LPiriDhR45ajNn0Mglc/sxwHtRmi4UTtgN48UAWS+RWqblZxtM9GF+pT1ZpHNearzfRtDhArLUyZBGGD5h31Wy7DZEKrahLaXZgGfAY+3TzuepPyyAR6fBcaKZrXL1hOE0Pv4S6qraVrD9BLPxLckHznkHkvAwOIfCEk7SNyRKBJTPiqRbIoxejMaufsxvynmgfcnbZR+T5JvRRTHCAKKdfLgGZwGc5gnxjW5Kuu1E/FhzyNdpz1SNpKp48ixo50cUrTBgdQo9zf4N4lpLX+t0FedoHsRmraHGIiRukgFQ0Jxu2UvSGaXKe1M1Os0mjBqj9jX4J6jqqVJyFQm/VX1ZjuWd7u7Dcf6llXdQ15Gg8GPV0w39G1CM9UOhrm70G3DXQ7mqJGvqvm++1JI8WJ469GIPIjc6KBxjW71wWYbXFIoqwQhckc2aqMmfCeI/6sooU6MbVQ9d1MWc57QUNyttJVB9dBSGeBz5YW7RG8Oh86kpQ8ZQI9tEON8jFw5x2x0Z0E6a8Ubwvu7gbc247BRa4H90yne8E9//0nEH3HR5YJdDZhI3TAfyte2QJRjMXoPHHJswTJwtcVfTQ4dZtsX4x2nKaGqKbfTFH7bEJ6/qW6thu3LejmQ0sG5r4xge7WgQ5+CzU03HOO2ysv7TDB7AFujE8k7reR6R4H78mwuTKZkEA3QLsDsZxWVDJao2mkUYk3jFy8WJ9V0iowqESLLAtPZiKSnKPU3ZTGLJRRi1gvK5U6tklyTohlkiTdskb7TAu9lgcZbHt+00eNuNMQSztAoFvAnXT+PMztDMJFnP0gn3hLxgm0pDTCRoQO0qekNaSYOesipid3uFFDU6+oGQTKklkfJ4ydQ2Ru2gnD5mBHqR5KUj6i7HYpWSSGsLzbQlkh5yaku3AUx3c4DGM+llTsOdy0CRTN5jPIVPaAAZo/A/kFzp3iCn8WozNNJehMqvbpuXHIAR7KERqb2uFMzXdHk46NCPabLXfe9aTtyuatyW6XoOmBTlglcbEhpjVhS9tN6olRDLAUynGnItCEx322y1KohBsp0z/RvIgsa3EmePMCM+ELMN81ZaOU1rDSsCuGaa6Lqa3Y1KCaRGpRggEDzF/EmgxIe0Cpm5ohOTgBg55t09fGAo8uAPOmFNtxRVPXSTRQv7B08uAXq90hge5uufB4AuYnwZ1vfEr5bichF0H+kRcCNW0UNaXDDQZ0lDWGhFyKqa2siEiQDSJJJLN+Ikvcjotz3BiMY22MzldUnuEHdUHJRFsuasqQdZQ1eU4z3za1XR15TsL8+VBdABFjE96m+f6gkNPAP+jGxZB8DzETaDAaCnmVIPzojLJCCrNKY2tK2myZ/g/qQOpq7VAC89nLA5bhASk/aXbgKCa2Gsi3LuXRRENsWM67k2DqwkQjTyPPMvqgPbL8UIL3qCPP1dJAL58JVYeIR6r0WCTQf6NM+JEnBuK4ABYGskygUbXQuqJZ6OaChkI0ErUDlxXtYTYBAXgapXfgWiNl8iwpnx8wGLA2arQZG+SclPzTSjOt+tf51W6MOQ0RRJ6eT6/qH1qGiH69qIHurLn+NLg+k6a4A9yD24KAzI4nXW6bE5JEV5hf0ii1IqSTZwlTIdqDqinKo73f8Qejipm/Afwn3dXFqKiuLiWNid5JxCGLQUkz98rgtypf7AARpV0nm1N6jmpGN2NqnwXwjyavWlf9yoA3QIOm2bRBq9U6SxMB+r4Uo1fvJeQ4IRcL2SSkIeR+Ia9mJKL9NUL+XMhulN+HNPedlOHo9KbR3wtK1O2wyN0jSrolg4jg4zEikssY6UL9qeU0/Z0ugnwx5N1UU8j/RECU+7jSMnj3SaXP0nMcTR3MhETHrxm8O62gBvq4hlfT9At9iORHmukEdHbfG1xH45Wa/3ewlIenaZR5kOR+GlWnoP2kxF00v388w9pCk0ZZR9IeHI0ZpI72YYdvbVJMc51PaFLtMwt74OOawA3SXgaUOpbjYxZDrIU8mdu2p01qGtM9qvUROSCy1PaLivUzbvA7XwJFX0f013yxg5WITvYPk/ynzz2Yrx01soK+Q/J7DVxPAFWeI+JGwjSNAIXpbpMzAvWIp6Q00FFlrqekzFOGkdWoQswVmH/srOqI34Bkq99pEUyY6VdQyhAFnikvL0zUYM53Ng0Xo26gYJnwRzSDUZzTNOOQpzzFOCGVzTEiUTJVddgn4wepdUL296mbFRnPt3oYWi3AzJ6JcEhbLcAEVZ9ZtnA4WbHD9VaxcDhfQXMgmXeg23jM6YEk5raNNMcD2lMS0R3eNhHz0EDdYXTllp3D5HzTQbP5KdAfvrYbMHRRnHAh7NmM53tzwMJMVTGzo6xyqhP6fZJG0qeYjaMxtZFumvC9FszfJrTv1qpBe2CTPGmf6nuoQPKF1BIEr5JHge5spvUx2uCk5t154e/0fqLEpE9o2HsNa6CttZp6eTAneVfhaBY6pi1oI32a0b9iIc/Tre4fD11OkFYtZMFyPEcaaMHniGmbx0VPxFzwqmjSqlm2RHwXo3oC5vRYA9VroI/nVGsoaybq4wRR2BQy+jch3s6RkuXFjyLlq0oStkPIAbtxNPuhO3FMw+o17sKkWpYBMN/WK+dlAtoj2nuaZzPGO65Zmj8FZU1gY8AilSMvInmk8C7l5rdZfIm4wIMR7++lBZ00CQ9X6n9rKb39ckygDcUkrkF7FPRGzMZVVSbb1YWUOFijaaymncgh89tbBPIjjYGA/KXhgK4GHOkEZpW6s7Xg00/kJ8Mza8MCceDgtc5nEGtAvMPodNsvmxBxJ1FIeXWxHzyTfrVswl/lo1InVf3fI+TnQrZQmi8K+a6Q3S2bLWeQL6mHZ4RcLmSZZZ86xNdyYsKXAszHmYT+gX4mzrRF89kzE0uSVMk8G/e5v5XAbK6m5O840mETPq0FH7868t57n+Zd1TWLMrbyVuuQf2rFp/+88b1305mam14XsmuCB58qEaeKp4SstFTALwe8oDuFbB0z3V2pDvLkRK+KX+OtpjBfmDTdTqDWBQItdZhA6ylvRhix8B5mEs4vlzpYn94csDcYzMhz/GFkcWYC8nlak96s9P/NFgp2lJTv14S8oHnmJTHT1g0qOCDslCMCradkWegacRR3KFNtPynGqcP3GWjcfSktwPhpMdVWZyyPiuX0HY2WG3UQc1p2tew0ymkkYQ14U8yEP6xpyIcI2UrICZK5vYclghgS8lYhOwrZoAwGcVfNN2nq446ceRGYbC9M6sc3Qu+hmALZ+5mL43R/lTqOZzY6Fuuq1rJvCnqEnnZnL9Ozqik+oxqBNCfo/kIrHY+AGQukHEuWvMGiLvBMdDVW56Pgbq+Miq8J+Zj0+WBlAvo8cIOPnCjkhwkmeido4Wilsjh1k5BjlMWlJyOm/Qi0B37GY0n+JmeeBHVpInwA0j3HJgm8KE4rffxaZzvgF1qDuSAg/cAIQ4HeWa/GnxPf1xQEh/ZL4j9qujiYKmQCPQrcg99U/LGQX0VM9xIhn6b/n9S4A+1LK+VHCLk9Qf7/A9xjQ85Wrv+VkC/T/7i1E7d7vhwh3QPBjcSke3mbc9jQK4arpQxGHjBOfXGWvEK6Ntgtlf6/Ddy978uVe9bGIFB5L/su0B7U9xRwd/T8ImH+fy7kI+CGyHtJSV/WUl+OmK7OFQL3z/8spw1umPscYwFhdVYy0iP9/4qPdnWBhlTD8GNFzUZz/Sxwgyp/QsjHSe1+KWH+vwTu4W7fFfJeIQcIuULIYfQ9qtcXR0xzOegDP2+GbB4kx2AwMkCgiBs192DA5XMjpovEhY6zd9Lndwq5BtxIS5eDG73+ixbyj/v48Zz3g4ng/kfIX9B3r5IpPx4xzXNBH2T6Rm4uDAZDhjwHisBdPBgncyflPiS+/WJoYEvJxD4c3J1Id4F7PKltMsL8/qWQQ4RsB+7E9dWgn8cMAh5XjHOzeyrXZ8Bd3HiOmwyDwfAjUASuZv2tj2b2zwu8Ps4RcqXm+mch4lkpDAZjcRLom0gL3V65fp+QdwjZskDrYishv4b2GADPk/b5DDcXBoMho0dzDYniCs11JJbTFnBdnAb6ACpXMHnGwriB1Cw9qwSut0VejswuUPkLHXgHcfNnC9UY1lun6icxlvpc/3twV6KXKdexwaPv5WMLrLPv7tOZX6a6YETHak1HwoW+huXnOPTu4gTiZeiBC8CDXA3xCRQJ8ioh5yvX0acTV9Nxl09roUxjUJl0h8ddtQAHi4UG1FLW54w8m5AhX0YNbIb2G1yA9RNowntAd6PXNNePhvYtn90Auhrh/ORvINl20IuoTCpeozpgZBuT0J3TOxcyHK6C5ASKp1de5vMdbtU8tMt5Ry0YXZb2B3cnUhwcSmXR4TKqA0Z60M1bokY546MV1ZXPGNhWnlNVA/Z60cO9+yYk7Uo3J4vPrQTkt6rc7332UAL/+b7xgM8VTV4wr9NUH9MBRDdhoKGXlDz6zUN7+ShK31dgbk5SRQXaT9RE9En1WIX2ANdlpZy684bGc9GCQ6KNYPSk230irPxWyA4xo5hgoONvCjkv5u8xxueklJdPxUhjByqDDrdT2YHFavSekiYoblkTVm5Ec12+t0jRhgpKmLW6EunIi9rkGEaWqocE8O3zCVMnR3SqBoRgC/qsi05VlP4v+4TgqwSUZ5p+W5Dyp4aTq2rSGPcJN+hoYoOOaK7L96rvvaJ5J8UY9ZMJ6QnhV3RZOgP0p1BiQJBrwXU+jwKMcHQbzVt9BdyITFGBWzhX0f+4Rz2qf+rWlPd9Nd89S2Xewgpi6sDzldSjPPBzv+Y6ajHeKYsbqP00pe9n6do6zTPC5ke9haj+AC1OF4xlGNI596hCZZuUyrBBc9860J88WaP6ayr11KAyyvUxpKkzv3dV1pj5Y8r1Aj3Pr843aOasJzXp5N6E94A+oR/1+e5EKniUvfJIUF7oOfS9/J6QDxn+Fk31b8D8veq4t/6JCM9fTnn2I+6PUpkZnZm/LCqdzwt/VlDM9UlNJzVJ04TgkHAGAzp9mdpMJ1Ak8hvUlKGg5Knhk+d+MF+8MV18G1UGtTKRqu76WEDZJmHuiGdZQFKKFhSBIv5FmX+ScSy4wUN2NEwLIz6dAK5jvqcN/iu4u4CC8om/wb3uH5Guf1/I1yOUd0fK67E+39eprIzOoSFpHl6nVDWbUgQCm4VoiyCethdEtCugM6v8nia83kcDrCqa3FDM55Rhzj/T1EezKWniiF4iz1lFI+0F//PYnZDBdNNCJVDEheCeqqnDkUJuBX0QDh0wRugxkhaBJHolkdcBEtkdQS/4HnADkMgm93eEnAnm7lQ7Ux6P9Pn+Xiojo3tmfK+kacqazZqATqnrpKZkp9P2uokgTXgY5k4gLdA9zRja7Qj9dlCSKO+qrCHCMeX6bMDgNgtzJ8Kq0sxb410a4V50GcLdOuM+2ua7wXWUfj+40dzDME1kdr2kup9CgqS4xOd3GNDjIjLlTckTo+rfLOSPfL7/PZXteeazrpnxqlnuaTw6c70ZYMYXwTzALhKWyTG4m4loGwZaVVwXoLKBJjxKGvOqmNpnFZL5zI7C3Kr9mHK9rrmue9f1hdR4eyLejwGQ3wduGDkdkKCmINgVRAZGeTqctE+ZDHXkic/8OyFvJ7PdhDyXUIOZCiDPp6lMvwBGN834msaEG6MOp3bKIbpeUIirHsEMDJv3VPNXhPnuOJ657Sj3lZRrDoRvWcVymOz+GSLTvQjxF68cDamamtrewKWa6V4drjOwFLx3p55dn8tgPUtj/OZO0hxRo9vTx1SuE3Hh2Ud3haSHq+g4r4nh5zB+Jzq1Y1g9XGx6SMhPwd0+egNEiyz/LnD3sR8RcM8jpDHfxRzWdTN+BNod4kepY41qNJl+DYENGZr6RRrki5qO2w96x/y11K5rkvk8CO27dobIShulvDl037oQMi9Au+/jpKJNe+Zv3GNlBqme1bwVNaZ2XSLNQeVd6RZ7xkhLDxuQhumeEcWsH8pjw9VFYzLFSiLR/QPuwd086HKEzuovdahM21Gn6A8ZIO4nsp5m/mIkAJLeao0GV4R0DsKbgGT7/r28NSF4zrFkcE+n63VBEShiN3BPwAxzP0CSwm2R3w8w/5MC97Lj/CnOj+4dcu//kub5KPd/Ro46uncC5kI/MdTbPbbgCdQr7A9DTGUP6P/570KuIzMi6aLN9mRanUHapMmUxB1CjgN37pPByBOB4rPWQw5XqyNwyQbSfk2nY3JPoIitqOCD0B6I2Q8vEvHeQBoq7jt/BOaf7y4DyXEP0i73EXI8uL6hpvvgX6KphC+BPkgKgxEHRehcMJNOPqtb6PbUQVcI1AOee4TbM9fE/P0WItEHYS6Qx94kexBRx8GPwA3N1+T+zmAwskqgHtYQke7V5fL9jjTjH/CrZjAYttGTUrro0oB+l7Uumcv4TAz88E4mTwaDkTcNVAb6hZ4M7kIPOs0vSek5WBCM8oQLVBig5Cl+vQwGI+8EKgN9R08nMj3QUpq4y+jb4O6N5wDIDAZjwRKoDNwp9EFwd2DsLYnfNrIZmFtcQmmCu4J/N79GBoOx2AjUDztIZNqSCJMDfTAYjEzh/wUYAG3b9pIINEpcAAAAAElFTkSuQmCC"/>
</div>
    </header>
    <div id="timer"></div>
<article id="home" class="pagewidth">';

function EndHtml($struct) { return "<div id='end'>Sprawdź odpowiedzi!</div>
    </article>
	<article id='score' class='pagewidth'>
        <p>Wynik testu</p>
		<span>błąd - nie oszukuj!</span>
		<div id='showAnswers'>Pokaż odpowiedzi</div>
		<div id='yourAnswers'>
			<h3>JAK MA GRZESIEK NA NAZWISKO?</h3>
			<h4>Pytel</h4>
			<h4>Schmidt</h4>
			<h4 class='yes'>Mrózek</h4>
			<h4>Nie Ma :3</h4>
		</div>
    </article>
    <footer class='pagewidth'>
        <p>Copyright &copy; 2015 Rafał Schmidt and Grzegorz Mrózek</p>
    </footer>
    <script>
		$(document).ready(function() { 
			var quiz = ".JSON_encode($struct).';
			var seconds = quiz.questions.length*quiz.quizTime;
        document.getElementById("timer").innerHTML = seconds + "s";
        function display(){
            document.getElementById("timer").innerHTML = seconds + "s";
            if (seconds > 0) {
                seconds--;
            } else {
                alert("koniec czasu!");
                finishWentox();
            }
        }
        var timer = setInterval(display, 1000);
        
        var quiz_answers = []; //Odpowiedzi wybrane
        $(document).on("click", "#end", function() {
            finishWentox();
        });
		
		function finishWentox(){
			window.clearInterval(timer);
			$("input").each( function(index, input) { input.disabled = true; } );
			giveMeMyAnswer();
			giveMeScore();
			$("#score").show();
			hideFullBox("#score");
		}
		
		function giveMeMyAnswer(){
			quiz_answers = []; //Reset odpowiedzi
			$("section").each( function(index, section) {
				var answers = { "type" : "", "answers": [] }; //Odpowiedzi jednego pytania
				$(section).children("label").each( function(labelIndex, label) {
					var answer = { "value" : "", "isChecked" :""}; //Schemat odpowiedzi
					
					answers.type = ( $(label).children("input").attr("type") == "radio" ? "1" : "0" ); //Rodzaj checkbox/radio 1 = radio
					answer.isChecked = $(label).children("input").is(":checked"); //Czy jest zaznaczony
					answer.value = $(label).children("input").attr("value"); //Tytuł
					
					console.log($(label).children("input").is(":checked"));  // Boolean true
					console.log($(label).children("input").attr("value"));
					
					answers.answers.push(answer); //Wpisanie jednej odpowiedzi do odpowiedzi jednego pytania
				} );
				quiz_answers.push(answers); //Wpisanie do całego arkusza
			} );
		}
		
		function giveMeScore(){
			var score = 0;
			document.getElementById("yourAnswers").innerHTML = "";
			$(quiz.questions).each( function(index, value){
				document.getElementById("yourAnswers").innerHTML += "<h3>" + value.title + "</h3>";
				var answerIsWrong = false;
				$(value.answers).each( function(counter, answer){
					document.getElementById("yourAnswers").innerHTML += "<h4" + (answer.isTrue ? " class=\'yes\'" : "" )  + ">" + answer.value + "</h4>";
					if( answer.isTrue != quiz_answers[index].answers[counter].isChecked ) answerIsWrong = true;
				});
				if(!answerIsWrong) score++;
			});
			$("#score span").text(score+"/"+$("section").length+" | Uzyskałeś: "+(score/$("section").length)*100+"%");
		}
		
		function hideFullBox(item){
			$(document).mouseup(function (e){ //close (click outside)
				var container = $(item);
				if (!container.is(e.target)	&& container.has(e.target).length === 0) 
				{
					container.hide();
					$("#showAnswers").show();
					$("#yourAnswers").hide();
				}
			});
		}
		
		$(document).on("click", "#showAnswers", function() {
			$("#showAnswers").hide();
			$("#yourAnswers").show();
		});
		});
        </script></body></html>';}

?>