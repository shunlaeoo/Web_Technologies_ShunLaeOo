import { Routes, Route, Navigate } from 'react-router-dom';
import './App.css';
import Header from './components/Header';
import Login from './components/LoginPage';
import Register from './components/Register';
import Recommendations from './Recommendations';
import { useAuth, AuthProvider } from './context/AuthContext';

function Home() {
  return (
    <div className="hero-section home">
      <Header />
      <div className="hero-content p-10">
        <h1 className="text-4xl font-bold leading-tight">
          FROM SETTING<br />
          GOALS TO<br />
          BREAKING BARRIERS
        </h1>
      </div>
    </div>
  );
}

function AppRoutes() {
  const { isAuthenticated } = useAuth();

  return (
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
    </Routes>
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
