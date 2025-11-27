<footer>
    <p>© 2025 Un Rincón en Velaris</p>
</footer>

<!-- ✨ EFECTO DE ESTRELLITAS -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.social-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const star = document.createElement('span');
            star.textContent = "✨";
            star.style.position = "absolute";
            star.style.left = e.pageX + "px";
            star.style.top = e.pageY + "px";
            star.style.transform = "translate(-50%, -50%)";
            star.style.pointerEvents = "none";
            star.style.fontSize = "20px";
            star.style.animation = "starPop .7s ease-out forwards";

            document.body.appendChild(star);
            setTimeout(() => star.remove(), 700);
        });
    });
});
</script>

<style>
@keyframes starPop {
    0% { opacity: 0; transform: translate(-50%, -50%) scale(0.3); }
    50% { opacity: 1; transform: translate(-50%, -80%) scale(1); }
    100% { opacity: 0; transform: translate(-50%, -120%) scale(1.2); }
}
</style>

</body>
</html>
