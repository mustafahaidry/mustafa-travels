PHASE 1 - Supplier-independent flight search shell

Upload:
flights.php
api/airports.php
api/data/airports.json
api/flight-provider.php
api/providers/FlightProviderInterface.php
api/providers/DuffelProvider.php
api/providers/PKFareProvider.php
api/providers/TravelfusionProvider.php

Current provider defaults to Duffel.
When PKFARE or Travelfusion credentials arrive, we only replace/fill their adapter files.
