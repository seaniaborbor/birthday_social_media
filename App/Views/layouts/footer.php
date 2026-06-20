    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container" style="max-width: 1280px; margin: 0 auto; padding: 0 20px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px;">
                <div>
                    <h3 style="font-size: 18px; margin-bottom: 16px;"><?= get_association_name() ?></h3>
                    <p class="font-mono-courier" style="font-size: 13px; opacity: 0.7;">
                        <?= setting('motto', 'Unity Through Birth') ?>
                    </p>
                    <div class="stamp" style="position: static; display: inline-block; margin-top: 16px;"></div>
                </div>
                
                <div>
                    <h4 style="font-size: 16px; margin-bottom: 16px;">Quick Links</h4>
                    <ul style="list-style: none;">
                        <li><a href="/" style="color: var(--color-text); text-decoration: none; font-size: 14px;">Home</a></li>
                        <li><a href="/members/directory" style="color: var(--color-text); text-decoration: none; font-size: 14px;">Directory</a></li>
                        <li><a href="/events" style="color: var(--color-text); text-decoration: none; font-size: 14px;">Events</a></li>
                        <li><a href="/contact" style="color: var(--color-text); text-decoration: none; font-size: 14px;">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 style="font-size: 16px; margin-bottom: 16px;">Contact</h4>
                    <p style="font-size: 13px;"><?= setting('association_address', 'Monrovia, Liberia') ?></p>
                    <p style="font-size: 13px;">📧 <?= setting('association_email', 'info@bmams.com') ?></p>
                    <p style="font-size: 13px;">📞 <?= setting('association_phone', '+231000000000') ?></p>
                </div>
                
                <div>
                    <h4 style="font-size: 16px; margin-bottom: 16px;">Follow Us</h4>
                    <div style="display: flex; gap: 12px;">
                        <?php if (setting('facebook_url')): ?>
                            <a href="<?= setting('facebook_url') ?>" target="_blank" class="btn-vintage" style="padding: 4px 8px;">FB</a>
                        <?php endif; ?>
                        <?php if (setting('twitter_url')): ?>
                            <a href="<?= setting('twitter_url') ?>" target="_blank" class="btn-vintage" style="padding: 4px 8px;">X</a>
                        <?php endif; ?>
                        <?php if (setting('instagram_url')): ?>
                            <a href="<?= setting('instagram_url') ?>" target="_blank" class="btn-vintage" style="padding: 4px 8px;">IG</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <hr class="dotted-divider">
            
            <div style="text-align: center; font-size: 12px; opacity: 0.6;">
                <p>&copy; <?= date('Y') ?> <?= get_association_name() ?>. All rights reserved.</p>
                <p class="font-mono-courier">Built with ❤️ for the <?= get_birth_month() ?> born community</p>
            </div>
        </div>
    </footer>
    
    <script>
        // Theme Toggle (No AJAX - uses POST)
        document.getElementById('theme-toggle')?.addEventListener('click', function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/theme/toggle';
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= csrf_token() ?>';
            csrfInput.value = '<?= csrf_hash() ?>';
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        });
        
        // Dropdown Toggle
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = event.target.closest('.dropdown button');
            if (dropdown && !button && dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
            }
        });
        
        // Dismiss Announcement (Traditional POST)
        document.querySelectorAll('.dismiss-announcement').forEach(button => {
            button.addEventListener('click', function() {
                const announcementId = this.dataset.id;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/announcement/dismiss';
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '<?= csrf_token() ?>';
                csrfInput.value = '<?= csrf_hash() ?>';
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'announcement_id';
                idInput.value = announcementId;
                form.appendChild(csrfInput);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            });
        });
        
        // Simple Confetti for Birthday (No AJAX)
        <?php if (isset($showConfetti) && $showConfetti): ?>
        (function() {
            const canvas = document.getElementById('confetti-canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            
            const colors = ['#1d4ed8', '#eab308', '#dc2626', '#10b981', '#8b5cf6'];
            const confetti = [];
            
            for (let i = 0; i < 150; i++) {
                confetti.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height - canvas.height,
                    size: Math.random() * 8 + 4,
                    color: colors[Math.floor(Math.random() * colors.length)],
                    speed: Math.random() * 5 + 2,
                    rotation: Math.random() * 360
                });
            }
            
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                let active = false;
                
                for (let i = 0; i < confetti.length; i++) {
                    const c = confetti[i];
                    if (c.y < canvas.height) {
                        active = true;
                        c.y += c.speed;
                        c.rotation += 5;
                        ctx.save();
                        ctx.translate(c.x, c.y);
                        ctx.rotate(c.rotation * Math.PI / 180);
                        ctx.fillStyle = c.color;
                        ctx.fillRect(-c.size/2, -c.size/2, c.size, c.size);
                        ctx.restore();
                    }
                }
                
                if (active) {
                    requestAnimationFrame(animate);
                }
            }
            
            animate();
            
            setTimeout(() => {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }, 8000);
        })();
        <?php endif; ?>
    </script>
    
    <script>
        // Hamburger menu toggle for mobile navigation
        function toggleNavMenu() {
            const navLinks = document.querySelector('.nav-links');
            const hamburger = document.querySelector('.hamburger-toggle');
            
            if (navLinks) {
                navLinks.classList.toggle('active');
            }
            if (hamburger) {
                hamburger.classList.toggle('active');
            }
        }
        
        // Close menu when a link is clicked
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                const navLinks = document.querySelector('.nav-links');
                if (navLinks) {
                    navLinks.classList.remove('active');
                }
                const hamburger = document.querySelector('.hamburger-toggle');
                if (hamburger) {
                    hamburger.classList.remove('active');
                }
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            const hamburger = document.querySelector('.hamburger-toggle');
            const navLinks = document.querySelector('.nav-links');
            
            if (hamburger && navLinks && 
                !hamburger.contains(e.target) && 
                !navLinks.contains(e.target)) {
                navLinks.classList.remove('active');
                hamburger.classList.remove('active');
            }
        });
        
        // Handle window resize - close menu on desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                const navLinks = document.querySelector('.nav-links');
                const hamburger = document.querySelector('.hamburger-toggle');
                if (navLinks) navLinks.classList.remove('active');
                if (hamburger) hamburger.classList.remove('active');
            }
        });
    </script>
</body>
</html>