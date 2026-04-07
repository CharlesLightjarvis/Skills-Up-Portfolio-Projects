# Personal AI Assistant with Laravel

A personal AI assistant built with [Laravel](https://laravel.com) and the [Laravel AI SDK](https://laravel.com/docs/ai). This is the companion repo for the YouTube video:

📺 **[Watch the video](https://youtu.be/DOABX6QYsYg)**

> **Note:** This is an MVP built for the video — a starting point to show what's possible, not a production-ready application. Some things (like the Vienna timezone, Forge project directory, or the calendar path) are customized for the demo — adjust them to your own setup.

## What It Does

An AI-powered personal assistant that can:

- 🔍 **Search the web** for real-time information
- 🕐 **Tell the local time**
- 📅 **Read your calendar** (ICS format)
- 📝 **Read and write files** (including its own soul/personality)
- ⏰ **Schedule recurring tasks**
- 💬 **Send Telegram messages**
- 🐚 **Run shell commands** (with safety restrictions)
- 🐙 **Create GitHub repos** and push code
- 🚀 **Deploy to Laravel Cloud**
- 🧠 **Remember conversations** across sessions

## Quick Start

```bash
git clone https://github.com/christophrumpel/laravel-ai-assistant.git
cd laravel-ai-assistant
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
```

Add your AI provider key to `.env`. The default provider is set to `anthropic` in `config/ai.php`, but you can change it to any supported provider (OpenAI, Google, etc.):

```
ANTHROPIC_API_KEY=your-key-here
```

Or switch the default in `config/ai.php` and set the matching key:

```
OPENAI_API_KEY=your-key-here
```

Start chatting:

```bash
php artisan chat
```

## Telegram Bot (Optional)

To connect the assistant to Telegram:

1. Create a bot via [@BotFather](https://t.me/BotFather)
2. Add to `.env`:
   ```
   TELEGRAM_BOT_TOKEN=your-bot-token
   TELEGRAM_CHAT_ID=your-chat-id
   ```
3. Deploy to a VPS (e.g. [Laravel Forge](https://forge.laravel.com)) and register the webhook:
   ```bash
   php artisan telegram:set-webhook https://your-domain.com
   ```

Deploying to a VPS like Forge has the added benefit that your assistant can actually work on the server — creating projects, running commands, and managing code.

## Scheduled Tasks

The assistant can schedule daily tasks. Just make sure the [Laravel scheduler](https://laravel.com/docs/scheduling) is enabled on your server — on most platforms like Forge, this is a single click.

## Customization

On first chat, the assistant will ask about your preferences and save them to `storage/app/soul.md`. You can edit this file anytime to change its personality and instructions.

## License

MIT
