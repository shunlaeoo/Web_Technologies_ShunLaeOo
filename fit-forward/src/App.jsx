import { Routes, Route, Navigate } from 'react-router-dom';
import './App.css';
import Header from './components/Header';
import Login from './components/LoginPage';
import Register from './components/Register';
import Profile from './components/Profile';
import Recommendations from './Recommendations';
import { useAuth, AuthProvider } from './context/AuthContext';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';

function Home() {
  return (
    <>
      <Header />
      <div className="hero-section home">
        <div className="hero-content p-10">
          <h1 className="font-bold">
            FROM SETTING<br />
            GOALS TO<br />
            BREAKING BARRIERS
          </h1>
        </div>
      </div>
      <footer className="bg-[#111]">
      < div className="text-center text-pink-500 text-xs py-4">
          &copy; 2025 FIT-FORWARD. All rights reserved.
        </div>
      </footer>
    </>
  );
}

function AppRoutes() {
  const { isAuthenticated } = useAuth();

  return (
    <>
      <Routes>
        <Route path="/" element={<Home />} />

        {/* Redirect if already authenticated */}
        <Route
          path="/login"
          element={
            isAuthenticated ? <Navigate to="/home" replace /> : <Login />
          }
        />
        <Route
          path="/register"
          element={
            isAuthenticated ? <Navigate to="/home" replace /> : <Register />
          }
        />

        {/* Only allow /home if authenticated */}
        <Route
          path="/home"
          element={
            isAuthenticated ? <Recommendations /> : <Navigate to="/login" replace />
          }
        />
        <Route
          path="/profile"
          element={
            isAuthenticated ? <Profile /> : <Navigate to="/login" replace />
          }
        />
      </Routes>
      <ToastContainer position="bottom-right" autoClose={2000} />
    </>
  );
}

function App() {
  return (
    <AuthProvider>
      <AppRoutes />
    </AuthProvider>
  );
}

export default App;
