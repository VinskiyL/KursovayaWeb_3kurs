import axios from 'axios'
import {Link} from 'react-router-dom'
import './main/main.css'
import imgBooks from './main/books.jpg'
import imgAuthors from './main/authors.jpg'
import Search from './main/search'


function Prof(){
    return(
        <>
        <div className = "result">
            <Search/>
        </div>
        <div className = "b_o">
            <Link className = "booking" to={'booking'}>
               <p>Бронирование</p>
            </Link>
            <Link className = "order" to={'order'}>
               <p>Заказы</p>
            </Link>
        </div>
        <div className = "main_tab">
           <Link className = "table" to={'books_table'}>
               <div className = "books">
                 <div className='img_div'>
                     <img className='img' src={imgBooks} alt="books" />
                 </div>
                 <h2 className = "h2">Книги</h2>
               </div>
            </Link>
            <Link className = "table" to={`authors_table`}>
                <div className = "authors">
                    <div className='img_div'>
                        <img className='img' src={imgAuthors} alt="authors" />
                    </div>
                    <h2 className = "h2">Авторы</h2>
                </div>
            </Link>
        </div>
        </>
        )
}
export default Prof