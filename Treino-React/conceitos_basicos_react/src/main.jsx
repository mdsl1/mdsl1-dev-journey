import { StrictMode } from 'react';
import { createRoot } from 'react-dom/client';
import './index.css';
import App from './App.jsx';
import PaginaParams  from './pages/paginaComParams.jsx';

// Rotas
import { createBrowserRouter, RouterProvider } from 'react-router-dom'
import { Header } from './components/header.jsx';
import { ConsumoAPI } from './pages/paginaConsumoAPI.jsx';
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
