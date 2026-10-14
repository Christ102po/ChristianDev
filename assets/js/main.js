(() => {
  'use strict';
  const toggle = document.querySelector('.menu-toggle');
  const nav = document.querySelector('#site-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      const expanded = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!expanded));
      nav.classList.toggle('open', !expanded);
    });
    nav.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
      nav.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
    }));
  }
  const items = document.querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    items.forEach(item => item.classList.add('visible'));
  } else {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, {threshold: 0.08, rootMargin: '0px 0px -25px 0px'});
    items.forEach(item => observer.observe(item));
  }

  // Decorative cursor effects are opt-in for fine-pointer devices only.
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)');
  if (finePointer.matches && !reducedMotion.matches) {
    const glow = document.createElement('div');
    glow.className = 'cursor-glow';
    glow.setAttribute('aria-hidden', 'true');
    document.body.appendChild(glow);

    const ring = document.createElement('div');
    ring.className = 'cursor-ring';
    ring.setAttribute('aria-hidden', 'true');
    document.body.appendChild(ring);

    let pointerX = -500, pointerY = -500;
    let ringX = -500, ringY = -500;
    let raf = 0;
    let pointerActive = false;
    const animate = () => {
      ringX += (pointerX - ringX) * 0.19;
      ringY += (pointerY - ringY) * 0.19;
      ring.style.transform = `translate3d(${ringX}px,${ringY}px,0) translate(-50%,-50%)`;
      glow.style.transform = `translate3d(${pointerX}px,${pointerY}px,0) translate(-50%,-50%)`;
      if (pointerActive || Math.abs(ringX-pointerX)>0.3 || Math.abs(ringY-pointerY)>0.3) {
        raf = requestAnimationFrame(animate);
      } else { raf = 0; }
    };
    document.addEventListener('pointermove', event => {
      if (event.pointerType !== 'mouse') return;
      pointerX = event.clientX;
      pointerY = event.clientY;
      pointerActive = true;
      glow.classList.add('active');
      ring.classList.add('active');
      if (!raf) raf = requestAnimationFrame(animate);
    }, {passive:true});
    document.addEventListener('pointerout', event => {
      if (!event.relatedTarget) {
        pointerActive = false;
        glow.classList.remove('active');
        ring.classList.remove('active');
      }
    });
    document.querySelectorAll('a, button, .project-card, .skill-card').forEach(el => {
      el.addEventListener('pointerenter', () => ring.classList.add('hovering'));
      el.addEventListener('pointerleave', () => ring.classList.remove('hovering'));
    });
    document.addEventListener('pointerdown', event => {
      if (event.pointerType !== 'mouse') return;
      ring.classList.add('clicking');
      setTimeout(() => ring.classList.remove('clicking'), 180);
    });
    // A spotlight follows the pointer inside cards without moving the content.
    document.querySelectorAll('.project-card, .skill-card').forEach(card => {
      card.classList.add('cursor-reactive');
      card.addEventListener('pointermove', event => {
        const rect = card.getBoundingClientRect();
        card.style.setProperty('--pointer-x', `${event.clientX - rect.left}px`);
        card.style.setProperty('--pointer-y', `${event.clientY - rect.top}px`);
      }, {passive:true});
    });
  }
  // Progress line across the top illustrates how far down the page you are.
  const progress = document.createElement('div');
  progress.className = 'scroll-progress';
  progress.setAttribute('aria-hidden', 'true');
  document.body.appendChild(progress);
  let scrollQueued = false;
  const setProgress = () => {
    const scrollable = document.documentElement.scrollHeight - window.innerHeight;
    const ratio = scrollable > 0 ? Math.max(0, Math.min(1, window.scrollY / scrollable)) : 0;
    progress.style.transform = `scaleX(${ratio})`;
    scrollQueued = false;
  };
  window.addEventListener('scroll', () => {
    if (!scrollQueued) { scrollQueued = true; requestAnimationFrame(setProgress); }
  }, {passive:true});
  window.addEventListener('resize', setProgress, {passive:true});
  setProgress();
})();
