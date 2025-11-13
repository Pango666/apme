import './bootstrap';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';

gsap.registerPlugin(ScrollTrigger);

// Smooth scroll
const lenis = new Lenis({ lerp: 0.08 });
function raf(t){ lenis.raf(t); requestAnimationFrame(raf); }
requestAnimationFrame(raf);

// Reveal genérico
document.querySelectorAll('[data-reveal]').forEach((el, i) => {
  gsap.from(el, {
    y: 24, opacity: 0, duration: .8, delay: i*0.06,
    scrollTrigger: { trigger: el, start: 'top 85%' }
  });
});

// Parallax hero
const heroImg = document.querySelector('[data-hero-img]');
if (heroImg) {
  gsap.to(heroImg, {
    scale: 1.08, yPercent: 8,
    scrollTrigger: { trigger: heroImg, start: 'top top', scrub: true }
  });
}





document.querySelectorAll(".card").forEach((el) => {
  gsap.from(el, {
    opacity: 0, y: 24, duration: .5, ease: "power2.out",
    scrollTrigger: { trigger: el, start: "top 85%" }
  });
});
