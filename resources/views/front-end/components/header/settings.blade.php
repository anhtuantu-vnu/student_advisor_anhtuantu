<div class="p-2 text-center ms-3 position-relative dropdown-menu-icon menu-icon cursor-pointer">
  <i class="feather-settings animation-spin d-inline-block font-xl text-current"></i>
  <div class="dropdown-menu-settings switchcolor-wrap">
      <h4 class="fw-700 font-sm mb-4">
        {{ __('texts.texts.settings.' . auth()->user()->lang) }}
      </h4>
      <h6 class="font-xssss text-grey-500 fw-700 mb-3 d-block">Choose Color Theme</h6>
      <ul>
          <li>
              <label class="item-radio item-content">
                  <input type="radio" name="color-radio" value="red" checked=""><i
                      class="ti-check"></i>
                  <span class="circle-color bg-red" style="background-color: #ff3b30;"></span>
              </label>
          </li>
          <li>
              <label class="item-radio item-content">
                  <input type="radio" name="color-radio" value="green"><i class="ti-check"></i>
                  <span class="circle-color bg-green" style="background-color: #4cd964;"></span>
              </label>
          </li>
          <li>
              <label class="item-radio item-content">
                  <input type="radio" name="color-radio" value="blue" checked=""><i
                      class="ti-check"></i>
                  <span class="circle-color bg-blue" style="background-color: #132977;"></span>
              </label>
          </li>
          <li>
              <label class="item-radio item-content">
                  <input type="radio" name="color-radio" value="pink"><i class="ti-check"></i>
                  <span class="circle-color bg-pink" style="background-color: #ff2d55;"></span>
              </label>
          </li>
          <li>
              <label class="item-radio item-content">
                  <input type="radio" name="color-radio" value="yellow"><i class="ti-check"></i>
                  <span class="circle-color bg-yellow" style="background-color: #ffcc00;"></span>
              </label>
          </li>
          <li>
              <label class="item-radio item-content">
                  <input type="radio" name="color-radio" value="orange"><i class="ti-check"></i>
                  <span class="circle-color bg-orange" style="background-color: #ff9500;"></span>
              </label>
          </li>
          <li>
              <label class="item-radio item-content">
                  <input type="radio" name="color-radio" value="gray"><i class="ti-check"></i>
                  <span class="circle-color bg-gray" style="background-color: #8e8e93;"></span>
              </label>
          </li>

          <li>
              <label class="item-radio item-content">
                  <input type="radio" name="color-radio" value="brown"><i class="ti-check"></i>
                  <span class="circle-color bg-brown" style="background-color: #D2691E;"></span>
              </label>
          </li>
          <li>
              <label class="item-radio item-content">
                  <input type="radio" name="color-radio" value="darkgreen"><i class="ti-check"></i>
                  <span class="circle-color bg-darkgreen" style="background-color: #228B22;"></span>
              </label>
          </li>
          <li>
              <label class="item-radio item-content">
                  <input type="radio" name="color-radio" value="deeppink"><i class="ti-check"></i>
                  <span class="circle-color bg-deeppink" style="background-color: #FFC0CB;"></span>
              </label>
          </li>
          <li>
              <label class="item-radio item-content">
                  <input type="radio" name="color-radio" value="cadetblue"><i class="ti-check"></i>
                  <span class="circle-color bg-cadetblue" style="background-color: #5f9ea0;"></span>
              </label>
          </li>
          <li>
              <label class="item-radio item-content">
                  <input type="radio" name="color-radio" value="darkorchid"><i class="ti-check"></i>
                  <span class="circle-color bg-darkorchid" style="background-color: #9932cc;"></span>
              </label>
          </li>
      </ul>

      <div class="card bg-transparent-card border-0 d-block mt-3">
          <h4 class="d-inline font-xssss mont-font fw-700">Header Background</h4>
          <div class="d-inline float-right mt-1">
              <label class="toggle toggle-menu-color"><input type="checkbox"><span
                      class="toggle-icon"></span></label>
          </div>
      </div>
      <div class="card bg-transparent-card border-0 d-block mt-3">
          <h4 class="d-inline font-xssss mont-font fw-700">Menu Position</h4>
          <div class="d-inline float-right mt-1">
              <label class="toggle toggle-menu"><input type="checkbox"><span
                      class="toggle-icon"></span></label>
          </div>
      </div>
      <div class="card bg-transparent-card border-0 d-block mt-3">
          <h4 class="d-inline font-xssss mont-font fw-700">Dark Mode</h4>
          <div class="d-inline float-right mt-1">
              <label class="toggle toggle-dark"><input type="checkbox"><span
                      class="toggle-icon"></span></label>
          </div>
      </div>

  </div>
</div>