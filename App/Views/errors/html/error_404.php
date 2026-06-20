<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Merriweather', Georgia, 'Times New Roman', serif;
            background: #fdf8ed;
            color: #1a1a1a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .wrap {
            text-align: center;
            padding: 40px;
            max-width: 600px;
        }
        
        h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 120px;
            color: #1d4ed8;
            margin-bottom: 20px;
        }
        
        p {
            font-family: 'Courier Prime', monospace;
            font-size: 16px;
            margin-bottom: 30px;
            color: #4a4a4a;
        }
        
        a {
            display: inline-block;
            padding: 10px 24px;
            font-family: 'Courier Prime', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: transparent;
            border: 2px solid #1d4ed8;
            color: #1d4ed8;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        a:hover {
            background: #1d4ed8;
            color: white;
        }
        
        .stamp {
            margin-top: 30px;
            font-family: 'Courier Prime', monospace;
            font-size: 10px;
            text-transform: uppercase;
            color: #eab308;
            opacity: 0.6;
            transform: rotate(-3deg);
            border: 1px solid #eab308;
            display: inline-block;
            padding: 4px 12px;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>404</h1>
        
        <p>
            <?php if (ENVIRONMENT !== 'production') : ?>
                Page not found.
            <?php else : ?>
                <?= lang('Errors.sorryCannotFind') ?>
            <?php endif; ?>
        </p>
        
        <a href="/">← Back to Home</a>
        <div class="stamp">PAGE NOT FOUND</div>
    </div>
</body>
</html>