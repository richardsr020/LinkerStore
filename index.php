<?php require "templates/header.php";?>

        <main class="px-3">
          <h1>Welcome to Linker App Store</h1>
            <p class="lead">Boost your productivity with our all-in-one software suite! 🚀 
              Management, automation, security—powerful tools for businesses and individuals. 
              Download now and streamline your workflow! 🔥</p>
            <p class="lead">
              <a href="#" class="btn btn-lg btn-custom">Explore</a>
            </p>
        </main>
        <!-- JavaScript pour générer les bulles avec des couleurs différentes -->
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const body = document.body;
        const numBubbles = 8;
        const colors = ['#712cf9', '#f9338e', '#4a90e2', '#8e44ad', '#f39c12']; // Couleurs des bulles

        for (let i = 0; i < numBubbles; i++) {
          const bubble = document.createElement('div');
          bubble.classList.add('bubble');
          
          // Position aléatoire
          const x = Math.random() * 100;
          const y = Math.random() * 100;
          
          // Couleur aléatoire
          const randomColor = colors[Math.floor(Math.random() * colors.length)];
          bubble.style.background = randomColor;
          
          bubble.style.setProperty('--x', `${x}vw`);
          bubble.style.setProperty('--y', `${y}vh`);
          
          body.appendChild(bubble);
        }
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<?php require "templates/footer.php";?>

        