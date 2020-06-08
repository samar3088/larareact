 @if (Request::path() == 'weddings')
     
       <!-- for search -->  
      <div class="col-12 alignsearch">
         <input class="form-control" id="myInputTextFields" type="text" placeholder="Search for wedding equipments..." style=""> 
         <button type="button" id="filterlisteditems"><i class="fa fa-search"></i></button>
      </div>

 @elseif (Request::path() == 'birthdays')

       <!-- for search -->  
      <div class="col-12 alignsearch">
         <input class="form-control" id="myInputTextFields" type="text" placeholder="Search for birthday equipments..." style=""> 
         <button type="button" id="filterlisteditems"><i class="fa fa-search"></i></button>
      </div>

 @elseif (Request::path() == 'equipments')

       <!-- for search -->  
      <div class="col-12 alignsearch">
         <input class="form-control" id="myInputTextFields" type="text" placeholder="Search for event equipments..." style=""> 
         <button type="button" id="filterlisteditems"><i class="fa fa-search"></i></button>
      </div>

 @else

        <!-- for search -->  
        <div class="col-12 alignsearch">
            <input class="form-control" id="myInputTextFields" type="text" placeholder="Search for event equipments..." style=""> 
            <button type="button" id="filterlisteditems"><i class="fa fa-search"></i></button>
         </div>
     
 @endif