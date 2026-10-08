# Ad Spy POC — search a company, see its Facebook ads

Search a company name -> pull its Meta Ad Library ads via ScrapeCreators -> show them in a UI.

## Run locally
1. Get a free API key (100 free requests) at https://app.scrapecreators.com
2. `cp .env.example .env` and paste your key
3. `php -S localhost:8000`
4. Open http://localhost:8000

## Files
- `api.php`   backend proxy (hides the API key, calls ScrapeCreators)
- `index.html` search UI + ad cards
- `.env`      secret key (git-ignored, never commit)

## Push to GitHub
```
git init
git add .
git commit -m "POC: search company and show Facebook ads"
git branch -M main
git remote add origin https://github.com/<you>/<repo>.git
git push -u origin main
```

## Next steps (for the paid phases)
- User accounts + login, saved searches / tracked competitors
- Database to cache results (saves API credits)
- Filters (active/inactive, media type, date), pagination
- Stripe subscriptions -> real SaaS
