import { useState } from 'react';
import Header from './components/header/Header.tsx';
import Footer from './components/footer/Footer.tsx';
import Home from './pages/home/Home.tsx';
import Articles from './pages/articles/Articles.tsx';
import Gallery from './pages/gallery/Gallery.tsx';
import Videos from './pages/videos/Videos.tsx';
import './App.css';

const App = () => {
  
  const [ currentPage, setCurrentPage ] = useState('home');

  const switchPage = (page: string) => {
    setCurrentPage(page);
  }

  const renderPage = () => {
    switch(currentPage) {
      case 'home':
        return <Home />;
      case 'articles':
        return <Articles />;
      case 'gallery':
        return <Gallery />;
      case 'videos':
        return <Videos />;
    }
  }

  return (
    <div className='appContainer'>
      <Header onNavigate = { switchPage }/>
      {renderPage()}
      <Footer />
    </div>
  );
};

export default App
