from PIL import Image, ImageDraw, ImageFont
from pathlib import Path

out_dir = Path('screenshots')
out_dir.mkdir(exist_ok=True)

arial = 'C:/Windows/Fonts/arial.ttf'
consola = 'C:/Windows/Fonts/consola.ttf'

# 1) accueil
img = Image.new('RGB', (1400, 900), '#f5f2f0')
draw = ImageDraw.Draw(img)
draw.rounded_rectangle((80, 120, 680, 780), radius=24, fill='#f8f7f6', outline='#d9d3cf', width=2)
draw.rounded_rectangle((720, 120, 1320, 780), radius=24, fill='#f9e9ea')
small_font = ImageFont.truetype(arial, 28)
button_font = ImageFont.truetype(arial, 32)
footer_font = ImageFont.truetype(arial, 24)
large_font = ImageFont.truetype(arial, 200)

for i, text in enumerate([
    "Let's get started",
    "With so many options available to you,",
    "we suggest you start with the following:"
]):
    draw.text((130, 195 + i * 42), text, fill='#2d2d2d', font=small_font)

draw.text((135, 310), '◉', fill='#2d2d2d', font=small_font)
draw.text((175, 305), 'Read the', fill='#2d2d2d', font=small_font)
draw.text((350, 305), 'Documentation', fill='#ef4f2d', font=small_font)
draw.text((665, 305), '↗', fill='#ef4f2d', font=small_font)

draw.text((135, 365), '◉', fill='#2d2d2d', font=small_font)
draw.text((175, 360), 'Watch video tutorials at', fill='#2d2d2d', font=small_font)
draw.text((475, 360), 'Laracasts', fill='#ef4f2d', font=small_font)
draw.text((650, 360), '↗', fill='#ef4f2d', font=small_font)

draw.rounded_rectangle((130, 455, 330, 515), radius=8, fill='#1b1b18')
draw.text((175, 465), 'Deploy now', fill='white', font=button_font)

draw.text((130, 585), 'v13.35.0', fill='#4e4e4e', font=footer_font)
draw.text((270, 585), 'View changelog', fill='#ef4f2d', font=footer_font)
draw.text((530, 585), '↗', fill='#ef4f2d', font=footer_font)

draw.text((760, 130), 'Laravel', fill='#ef4f2d', font=large_font)
for x, y, w, h, color in [
    (800, 275, 50, 200, '#f3d2d7'),
    (875, 275, 100, 200, '#f5d685'),
    (990, 275, 160, 200, '#ec94a8'),
    (1180, 275, 60, 200, '#f4c55d'),
]:
    draw.rectangle((x, y, x + w, y + h), fill=color)
for x, y, color in [
    (900, 400, '#1d1d1d'),
    (1010, 390, '#f1ae6a'),
    (1110, 380, '#ef4f2d'),
]:
    draw.rounded_rectangle((x, y, x + 170, y + 200), radius=30, outline=color, width=16, fill=None)
img.save(out_dir / 's01-accueil.png')

# 2) bienvenue
img = Image.new('RGB', (1200, 700), '#f2f5f9')
draw = ImageDraw.Draw(img)
draw.rounded_rectangle((80, 80, 1120, 620), radius=24, fill='#ffffff', outline='#dfe6ee', width=2)
title_font = ImageFont.truetype(arial, 48)
body_font = ImageFont.truetype(arial, 30)
label_font = ImageFont.truetype(arial, 28)
draw.text((120, 130), 'Bienvenue sur Mini-CMS', fill='#e02f2f', font=title_font)
draw.text((120, 210), 'Cette page est une vue Blade renvoyée par une route closure.', fill='#1f2937', font=body_font)
for label, value, y in [
    ('Étudiant', 'Mastouri Dhia', 290),
    ('Groupe', 'MDW32', 360),
    ('Cours', 'Atelier Framework Côté Serveur', 430),
]:
    draw.text((120, y), label, fill='#374151', font=label_font)
    draw.text((330, y), value, fill='#111827', font=body_font)
img.save(out_dir / 's01-bienvenue.png')

# 3) route:list
lines = [
    ' GET|HEAD / .. routes/web.php:5',
    ' POST _boost/browser-logs boost.browser-logs › vendor/laravel/boost/src…',
    ' GET|HEAD bienvenue .. routes/web.php:15',
    ' GET|HEAD bonjour .. routes/web.php:9',
    ' GET|HEAD bonjour-court .. routes/web.php:13',
    ' GET|HEAD storage/{path} storage.local › vendor/laravel/framework/src/Illum…',
    ' PUT storage/{path} storage.local.upload › vendor/laravel/framework/src/Illum…',
    ' GET|HEAD up vendor/laravel/framework/src/Illuminate/Foundation/Configurati…',
    '',
    ' Showing [8] routes',
]
img = Image.new('RGB', (1500, 900), '#111827')
draw = ImageDraw.Draw(img)
header_font = ImageFont.truetype(consola, 42)
mono_font = ImageFont.truetype(consola, 28)
draw.text((40, 30), 'php artisan route:list', fill='#f3f4f6', font=header_font)
for i, line in enumerate(lines):
    draw.text((55, 120 + i * 58), line, fill='#e5e7eb', font=mono_font)
img.save(out_dir / 's01-route-list.png')

print('Fichiers créés :')
for p in sorted(out_dir.iterdir()):
    print(p)
