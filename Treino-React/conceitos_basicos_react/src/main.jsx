import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import './index.css';

// Import de páginas e componentes
import App from './App.jsx';
import PaginaParams  from './pages/paginaComParams.jsx';
import ConsumoAPI from './pages/paginaConsumoAPI.jsx';
import { Header } from './components/header.jsx';

// Hooks usados para manipulação de Rotas
import { createBrowserRouter, RouterProvider } from 'react-router-dom'

// Aqui é criado um "container" com todas as rotas e o que elas devem renderizar
const router = createBrowserRouter([
  {
    path: "/",
    element: 
    <div>
      <Header />
      <h1>Hello world</h1>
    </div>
  },
  {
    path: "/app",
    element: < App />
  },
  {
    path: "/pag_params",
    element: < PaginaParams />
  },
  {
    path: "/consumo_api",
    element: < ConsumoAPI />
  }
])

// A função "RouterProvider" puxa as rotas do router e aplica no site
createRoot(document.getElementById('root')).render(
  <StrictMode>
    <RouterProvider router={ router } />
  </StrictMode>,
)
