<!doctype html>
<html lang="fr" class="h-100" data-bs-theme="auto">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LinkerStore</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 128 128%22><text y=%221.2em%22 font-size=%2296%22>⚫️</text><text y=%221.3em%22 x=%220.2em%22 font-size=%2276%22 fill=%22%23fff%22>sf</text></svg>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
      .cover-container {
        max-width: 42em;
      }
      .btn-custom {
        background-color: #712cf9;
        border: none;
      }
      .btn-custom:hover {
        background-color: #5a23c8;
      }

      /* Styles des bulles */
      .bubble {
        position: absolute;
        width: 6cm; /* Triple la taille des bulles */
        height: 6cm; /* Triple la taille des bulles */
        border-radius: 50%;
        opacity: 0.6;
        animation: float 10s infinite;
      }
      .flash-message {
          opacity: 1;
          transition: opacity 1s ease-out;
      }

      .flash-message.hide {
          opacity: 0;
          pointer-events: none; /* Optionnel pour désactiver les interactions pendant la disparition */
      }

      @keyframes float {
        0% {
          transform: translate(0, 0);
        }
        100% {
          transform: translate(var(--x), var(--y));
        }
      }
    </style>
  </head>
    <body class="d-flex h-100 text-center text-bg-dark">
        <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
          <header class="mb-auto">
              <div>
              <h3 class="float-md-start mb-0">LinkerStore</h3>
              <nav class="nav nav-masthead justify-content-center float-md-end">
                  <a class="nav-link fw-bold py-1 px-2 active" aria-current="page" href="#">Home</a>
                  <a class="nav-link fw-bold py-1 px-2 active" aria-current="page" href="#">Explore</a>
                  <a class="nav-link fw-bold py-1 px-2" href="templates/registration.html">SignUp</a>
                  <a class="nav-link fw-bold py-1 px-2" href="templates/logIn.html">Login</a>
              </nav>
              </div>
          </header>
