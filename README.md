## Horoscope

World famous astrologer Lolita needs a Horoscope-as-a-Service (HaaS) application, because she is tired of doing all these complex astrological calculations by hand.

Using Laravel and SQL database of your choice, create an application that:

- generates horoscopes for all 12 Zodiac signs for a given year. Each sign can have from 1 - really shitty day to 10 - super amazing day. Day scores are generated randomly for each day and stored in the database.
- shows a calendar for a given year and Zodiac sign. Days should be colored from #ff0000 (really shitty) to #00ff00 (super amazing).
- shows the best month on average (by score) for a Zodiac sign in a given year.
- shows which Zodiac sign has the best year (by score)

BONUS POINTS: generate a sentence that describes what happens to an astrological sign on a given day. Sentence should make sense, be relevant to the score of the day (1 - 10), and should not be repetitive.

DB Structure:
- horoscope_zodiac_sign
    - zodiac_sign (hold the zodiac sign)
- horoscope_year
    - generated_year (to determine if a particular year has the data already)
    - best_zodiac_id (it will store the best zodiac id for that particular year so that it don't need to calculate each time)
- horoscope_data
    - zodiac_id (foreign key of horoscope_zodiac_sign)
    - score (generated random score)
    - calc_day (to store each day of particular year)
[Note: zodiac_id and calc_day will be unique together]

How to Run:

`git clone git@github.com:erazon/horoscope.git`

`cd horoscope`

`sail up -d`

`php artisan migrate --seed`

API Endpoints:

`POST /api/generates/{year}` [It will generate random scores for all zodiac signs for a given year]

`GET /calendar/{year}/{zodiac_sign_id}` [zodiac calendar for a given year and zodiac sign id]

`GET /best-month/{year}/{zodiac_sign_id}` [best month for a given year and zodiac sign id]

`GET /best-zodiac/{year}` [best zodiac sign for a given year]