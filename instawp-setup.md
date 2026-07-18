# The Hidden Word — Site Setup

Marketing theme for **thehiddenword.org**. The site highlights the **100% free** Hidden Word Bible Lessons WordPress plugin (memorization, Bible reader, Verse of the Day, AI study tools, digests, and more—all in one plugin).

**GitHub:** https://github.com/brelandr/the-hidden-word-theme  
**Install zip:** https://github.com/brelandr/the-hidden-word-theme/archive/refs/heads/main.zip

WordPress.org Live Preview uses `assets/blueprints/blueprint.json` on the plugin SVN (installs this theme + optional demo-hub from GitHub).

## Quick checklist

1. Install and activate **Hidden Word Bible Lessons** (2.0+)
2. Activate **The Hidden Word** theme
3. **Appearance → Marketing Setup → Run Marketing Setup**
4. **Appearance → Customize** — set the plugin download URL (WordPress.org)

## Expected pages

| Path | Content |
|------|---------|
| `/todays-lesson/` | `[hwbl_lesson]` |
| `/verse-of-the-week/` | `[hwbl_verse_of_week]` |
| `/lesson-catalog/` | `[hwbl_lesson_list]` |
| `/read-the-bible/` | `[hwbl_bible_reader]` |
| `/memorize-a-verse/` | `[hwbl_memorize_verse]` |
| `/memorization-reviews/` | `[hwbl_memorize_reviews]` |
| `/verse-of-the-day/` | `[hwbl_verse_of_the_day]` |
| `/find-a-lesson/` | `[hwbl_study_finder]` |
| `/ask-a-question/` | `[hwbl_ask_question]` |
| `/login/` / `/register/` | Auth forms |

After upgrading the theme, Marketing Setup re-runs when `SETUP_VERSION` bumps (or use **Appearance → Marketing Setup**).
