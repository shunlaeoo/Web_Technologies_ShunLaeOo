import Bio from './Bio.js';
import Gallery from './Gallery.js';
import { Profile } from './Profile.js';
import TodoList from './TodoList.js';

export default function App() {
  return (
    <div>
      <Profile />
      <Gallery />

      <TodoList />
      <Bio />
    </div>
  );
}