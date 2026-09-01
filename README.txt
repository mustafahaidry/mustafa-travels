Mustafa Travels Flight Engine Upgrade

Upload/replace these files on the website:
1) /flights-v3.php
2) /flight-results.php
3) /api/airports.php

IMPORTANT:
- Keep your existing /api/duffel.php file and DUFFEL_API_KEY environment variable.
- Do not place the Duffel API key in browser-side JavaScript.
- The new /api/airports.php searches Duffel Places server-side, so worldwide airport suggestions are available.
- Back up the current files before replacing them.

After upload, test:
https://www.mustafatravels.org/flights-v3.php

Try airport searches:
Ahmedabad / AMD
Amritsar / ATQ
Jaipur / JAI
Lucknow / LKO
Kochi / COK
Hyderabad / HYD
Bengaluru / BLR
Chennai / MAA
Kolkata / CCU
Srinagar / SXR
Goa / GOI

Then run a BCN -> AMD flight search.
