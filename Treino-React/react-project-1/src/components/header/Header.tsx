import './Header.css';

type HeaderProps = {
    onNavigate: (page: string) => void;
};

const Header: React.FC<HeaderProps> = ({ onNavigate }) => {

    return (
        <header>

            <div className="container">
                <div className="logo">1</div>
                <h1>Projeto em React 1</h1>
            </div>

            <nav>
                <a onClick={() => onNavigate('home')}>Home</a>
                <a onClick={() => onNavigate('articles')}>Artigos</a>
                <a onClick={() => onNavigate('gallery')}>Galeria</a>
                <a onClick={() => onNavigate('videos')}>Vídeos</a>
            </nav>
        </header>
    );
};

export default Header;