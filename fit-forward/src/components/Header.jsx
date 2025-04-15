import { toast } from 'react-toastify';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

function Header() {
  const { isAuthenticated, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = () => {
    logout();
    toast.success('Logout successful!');
    navigate('/login');
  };

  return (
    <header className="header flex flex-col md:flex-row justify-between items-center gap-4 px-6 py-4 bg-white shadow-md">
      <div className="logo">
        <Link to="/home">
          <img src="/image/landscape.png" alt="Fit Forward Logo" />
        </Link>
      </div>

      {isAuthenticated ? (
        <div className="flex flex-col sm:flex-row items-center gap-3">
          <Link to="/profile">
            <button className="flex items-center logout gap-2 py-2 px-5 rounded transition">
              <svg 
                width="24" 
                aria-hidden="true" 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24"
              >
                <path 
                  stroke="currentColor" 
                  strokeWidth="2" 
                  d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                />
              </svg>
              <span className="text-sm">Profile</span>
            </button>
          </Link>
          
          <button
            onClick={handleLogout}
            className="flex items-center logout gap-2 py-2 px-5 rounded transition"
          >
            <svg
              className="w-4 h-5"
              aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <path
                stroke="currentColor"
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth="3"
                d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2"
              />
            </svg>
            <span className="text-sm">Logout</span>
          </button>
        </div>
      ) : (
        <Link
          to="/login"
          className="get-started bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition"
        >
          GET STARTED →
        </Link>
      )}
    </header>
  );
}

export default Header;