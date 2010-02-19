/**
 * iBegin Share $$SVN:VERSION$$ (Build $$SVN:REVISION$$)
 * For more info & download: http://labs.ibegin.com/share/
 * Created as a part of the iBegin Labs Project - http://labs.ibegin.com/
 * For licensing please see readme.html (MIT Open Source License)
*/

var iBeginShare = function() {
  var _pub = {
    // Change this to your base URL
    // This only affects a couple plugins, and realistically should be removed
    // from the share framework.
    base_url: './',

    close_label: 'X',

    text_link_label: 'Share',
    
    // STOP EDITING
    version_number: '$$SVN:VERSION$$',
    build_number: '$$SVN:REVISION$$',
    
    is_opera: navigator.userAgent.indexOf('Opera/9') != -1,
    is_ie: navigator.userAgent.indexOf("MSIE ") != -1,
    is_safari: navigator.userAgent.indexOf('webkit') != -1,
    is_ie6: false /*@cc_on || @_jscript_version < 5.7 @*/,
    is_firefox: navigator.appName == "Netscape" && navigator.userAgent.indexOf("Gecko") != -1 && navigator.userAgent.indexOf("Netscape") == -1,
    is_mac: navigator.userAgent.indexOf('Macintosh') != -1,
    http: null,

    /**
     * Creates an HTML element.
     */
    createElement: function(tag, params) {
      var el = document.createElement(tag);
      if (!params) return el;
      for (var key in params) {
        if (key == 'className') el.className = params[key];
        else if (key == 'html') el.appendChild(document.createTextNode(params[key]));
        else if (key == 'children') continue;
        else if (key == 'events') {
          for (var name in params[key]) _pub.addEvent(el, name, params[key][name]);
        }
        else if (key == 'styles') {
          for (var name in params[key]) {
            el.style[name] = params[key][name];
          }
        }
        else el.setAttribute(key, params[key]);
      }
      if (params.children) for (var i=0; i<params.children.length; i++) el.appendChild(params.children[i]);
      return el;
    },

    /**
     * Serializes form elements into an object-array.
     * @return {Object}
     */
    serializeFormData: function(form) {
        var data = {};
        var els = form.getElementsByTagName('input');
        for (var i=0; i<els.length; i++) {
            if (els[i].name) {
                if (els[i].type == 'text' || els[i].type == 'hidden' || els[i].type == 'password'
                    || ((els[i].type == 'radio' || els[i].type == 'checkbox') && els[i].checked))
                    data[els[i].name] = els[i].value;
            }
        }
        var els = form.getElementsByTagName('textarea');
        for (var i=0; i<els.length; i++) {
            if (els[i].name) data[els[i].name] = els[i].value;
        }
        var els = form.getElementsByTagName('select');
        for (var i=0; i<els.length; i++) {
            if (els[i].name) data[els[i].name] = els[i][els[i].selectedIndex].value;
        }
        return data;
    },
    /**
     * Initiates an XMLHttpRequest and executes callback(responseText)
     * @param {String} url Request url
     * @param {Object} params Request parameters in an object-array format
     * @param {Function} success Successful callback function
     * @param {Function} error Error callback function
     */
    ajaxRequest: function(url, method, params, success, error) {
      if (!method) var method = 'GET';
      var parameters = ''; // string version of params
      for (var key in params) {
          if (typeof params[key] == 'object')
              for (var i=0; i<params[key].length; i++)
                  parameters += (key + '=' + escape(params[key][i]) + '&');
          else
              parameters += (key + '=' + escape(params[key]) + '&');
      }
      if (method == 'GET') {
        if (url.indexOf('?')) url += '&' + parameters;
        else url += '?' + parameters;
      }
      _pub.http.open(method, url, true);
      _pub.http.onreadystatechange = function() {
          if (_pub.http.readyState == 4) {
              if (_pub.http.status == 200)
                  success(_pub.http.responseText);
              else
                  if (error)
                      error(_pub.http, _pub.http.responseText);
          }
      }
      _pub.http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      if (method == 'POST') _pub.http.setRequestHeader("Content-length", parameters.length);
      _pub.http.setRequestHeader("Connection", "close");
      _pub.http.send(parameters);
    },
    showLoadingBar: function() {
      containers.loading.style.display = 'block';
      containers.content_inner.style.display = 'none';
    },
    hideLoadingBar: function() {
      containers.loading.style.display = 'none';
      containers.content_inner.style.display = 'block';
    },
    hasClass: function(obj, className) {
        if (obj.className) {
            var arrList = obj.className.split(' ');
            var strClassUpper = className.toUpperCase();

            for (var i=0; i<arrList.length; i++) {
                if (arrList[i].toUpperCase() == strClassUpper) {
                    return true;
                }
            }
        }
        return false;
    },
    toggleClass: function(obj, className) {
      if (_pub.hasClass(obj, className)) _pub.removeClass(obj, className);
      else _pub.addClass(obj, className);
    },
    addClass: function(obj, className) {
      obj.className = (obj.className ? obj.className + ' ' + className : className);
    },
    removeClass: function(obj, className) {
      if (obj.className) {
        var arrList = obj.className.split(' ');
        var strClassUpper = className.toUpperCase();

        for (var i=0; i<arrList.length; i++) {
          if (arrList[i].toUpperCase() == strClassUpper) {
            arrList.splice(i, 1);
            i--;
          }
        }
        obj.className = arrList.join(' ');
      }
    },
    /**
     * Empties the content of an object.
     */
    empty: function(obj) {
      while (obj.firstChild) obj.removeChild(obj.firstChild);
    },
    /**
     * Updates the content of the share box
     * @param {HTMLObject|String} html
     */
    html: function(html) {
      if (!html) return;
      _pub.hideLoadingBar();
      _pub.empty(containers.content_inner);
      if (typeof(html) == 'string') containers.content_inner.innerHTML = html;
      else containers.content_inner.appendChild(html);
    },
    /**
     * Hides the share box.
     */
    hide: function() {
      if (active_tab && active_tab.plugin.unload) active_tab.plugin.unload();
      if (active_link) _pub.removeClass(active_link, 'share-active');
      active_tab = null;
      active_link = null;
      containers.box.style.display = 'none';
      containers.box.className = 'share-box-show';
    },
    /**
     * Shows the share box and (if obj is present) positions
     * it relative to the container.
     * @param {HTMLObject} obj
     * @param {Object} params
     */
    show: function(obj, params) {
      // if no plugins are active bail
      if (!_pub.plugins.list.length) return false;
      // if the current link is active bail
      if (active_link == obj) return false;
      // hide it first to stop the bug where active button still shows
      if (active_link) _pub.hide();
      active_link = obj;
      _pub.addClass(obj, 'share-active');
      containers.box.style.position = 'absolute';
      containers.box.style.display = 'block';
      containers.box.style.visibility = 'hidden';
      containers.box.style.top = 0;
      containers.box.style.left = 0;

      var curtop = curleft = 0;
      var border;
      curtop += obj.offsetHeight + 5;
      if (obj.getBoundingClientRect) {
        var bounds = obj.getBoundingClientRect();
        curleft += bounds.left - 2;
        curtop += bounds.top + document.documentElement.scrollTop - 2;
      }
      else if (obj.offsetParent) {
        do {
          // XXX: If the element is position: relative we have to add borderWidth
          if (_pub.getStyle(obj, 'position') == 'relative') {
            if (border = _pub.getStyle(obj, 'border-top-width')) curtop += parseInt(border);
            if (border = _pub.getStyle(obj, 'border-left-width')) curleft += parseInt(border);
          }
          else if (obj.currentStyle && obj.currentStyle.hasLayout && obj !== document.body) {
            curleft += obj.clientLeft;
            curtop += obj.clientTop;
          }

          curtop += obj.offsetTop;
          curleft += obj.offsetLeft;
        }
        while (obj = obj.offsetParent)
      }
      else if (obj.x) {
        curtop += obj.y;
        curleft += obj.x;
      }
      
      pagesize = _pub.getPageSize();
      if (containers.box.offsetWidth + curleft > pagesize.width) {
        // if the box is larger than the page width, set it to 20px on the left
        if (containers.box.offsetWidth > pagesize.width-20) {
         curleft = 20;
        }
        else {
          // otherwise set it to page width - box length - 20px
          curleft = pagesize.width-20-containers.box.offsetWidth;
        }
      }
      containers.box.style.top = curtop + 'px';
      containers.box.style.left = curleft + 'px';
      containers.box.style.visibility = 'visible';
      containers.box.className = '';
      for (var i=0; i<_pub.plugins.list.length; i++) {
        var cur = _pub.plugins.list[i];
        var valid = true;
        if (cur.requires) {
          // validate variables exist in the params for the plugin
          for (var z=0; z<cur.requires.length; z++) {
            if (!params || !params[cur.requires[z]]) {
              valid = false;
              break;
            }
          }
        }
        if (valid) cur.tab.style.display = 'block';
        else cur.tab.style.display = 'none';
        cur.tab.className = '';
      }
      active_tab = _pub.plugins.list[0].tab;
      active_tab.className = 'active';
      active_tab.plugin.render(_pub.showPlugin, params);
    },
    
    /**
     * Gets the page constraints
     */
    getPageSize: function() {
      return {
        width: window.innerWidth || (document.documentElement && document.documentElement.clientWidth) || document.body.clientWidth,
        height: window.innerHeight || (document.documentElement && document.documentElement.clientHeight) || document.body.clientHeight
      };
    },
    
    showPlugin: function(html, params) {
      _pub.html(html);
      var h2 = _pub.createElement('h2', {html: active_tab.plugin.label});
      containers.content_inner.insertBefore(h2, containers.content_inner.firstChild);
    },
    /**
     * Draws a button on an object immediately.
     * @param {HTMLObject} obj
     * @param {Object} params
     */
    drawButton: function(obj, params) {
      if (!params.link) params.link = window.location.href;
      if (!params.title) params.title = document.title;

      var link = _pub.createElement('a', {
        className: 'share-button',
        href: 'javascript:void(0)',
        html: 'Share',
        events: {
          click: function(e) {
            if (!e) var e = window.event;
            var obj = e.target ? e.target : e.srcElement;
            if (_pub.hasClass(obj, 'share-active')) iBeginShare.hide(obj);
            else iBeginShare.show(obj, obj.params);
          }
        }
      });
      link.params = params;

      obj.appendChild(_pub.createElement('span', {
        className: 'share-button-wrapper',
        children: [link]
      }));
    },
    /**
     * Draws a text link on an object immediately.
     * @param {HTMLObject} obj
     * @param {Object} params
     */
    drawTextLink: function(obj, params) {
      if (!params.link) params.link = window.location.href;
      if (!params.title) params.title = document.title;
      var link = _pub.createElement('a', {
        className: 'share-text-link',
        href: 'javascript:void(0)',
        html: _pub.text_link_label,
        events: {
          click: function(e) {
            if (!e) var e = window.event;
            var obj = e.target ? e.target : e.srcElement;
            if (_pub.hasClass(obj, 'share-active')) iBeginShare.hide(obj);
            else iBeginShare.show(obj, obj.params);
          }
        }
      });
      link.params = params;
      obj.appendChild(link);
    },
    /**
     * Attaches a button to an object when the page is loaded.
     * @param {HTMLObject|String} obj
     * @param {Object} params
     */
    attachButton: function(obj, params) {
      if (typeof(obj) == 'string') obj = document.getElementById(obj);
      _pub.addEvent(window, 'load', _pub.bind(function(e, obj, params){iBeginShare.drawButton(obj, params);}, obj, params));
    },
    /**
     * Attaches a text link to an object when the page is loaded.
     * @param {HTMLObject|String} obj
     * @param {Object} params
     */
    attachTextLink: function(obj, params) {
      if (typeof(obj) == 'string') obj = document.getElementById(obj);
      _pub.addEvent(window, 'load', _pub.bind(function(e, obj, params){iBeginShare.drawTextLink(obj, params);}, obj, params));
    },
    /**
     * Binds arguments to a callback function
     */
    bind: function(fn) {
        var args = [];
        for (var n=1; n<arguments.length; n++) args.push(arguments[n]);
        return function(e) { return fn.apply(this, [e].concat(args)); };
    },
    /**
     * Binds an event listener
     * @param {Object} obj Object to bind the event to.
     * @param {String} evType Event name.
     * @param {Function} fn Function callback reference.
     */
    addEvent: function(obj, evType, fn) {
      if (obj.addEventListener) {
        obj.addEventListener(evType, fn, false);
        return true;
      }
      else if (obj.attachEvent) {
        var r = obj.attachEvent("on"+evType, fn);
        return r;
      }
      else {
        return false;
      }
    },
    getStyle: function(obj, styleProp) {
      if (obj.currentStyle)
        return obj.currentStyle[styleProp];
      else if (window.getComputedStyle)
        return document.defaultView.getComputedStyle(obj,null).getPropertyValue(styleProp);
    },
    getContainer: function() {
      return containers.box;
    },
    plugins: {
      builtin: {
        bookmarks: function() {  
          var regex_repl = /[^a-zA-Z0-9_-s.]/;
          var bookmarks_per_line = 7;
          var getIcon = function(name) {
            return 'bm_' + name.replace(regex_repl, '').toLowerCase();
          }

          var services = new Array();

          return {
            label: 'Bookmarks',
            requires: ['link', 'title'],
            addService: function(name, url) {
              services.push([name, url]);
            },
            render: function(callback, params) {
              var link = escape(params.link);
              var title = escape(params.title);

              var row_sets = [];
              var tr = _pub.createElement('tr');
              for (var i=0; i<services.length; i++) {
                if (i % bookmarks_per_line == 0 && i != 0) {
                  row_sets.push(tr);
                  tr = _pub.createElement('tr');
                }
                tr.appendChild(_pub.createElement('td', {
                  styles: {
                    textAlign: 'center',
                    width: 100/bookmarks_per_line + '%'
                  },
                  children: [
                    _pub.createElement('a', {
                      title: services[i][0],
                      target: '_blank',
                      href: services[i][1].replace('__URL__', link).replace('__TITLE__', title),
                      html: services[i][0],
                      styles: {
                        textDecoration: 'none'
                      },
                      children: [
                        _pub.createElement('img', {
                          src: _pub.base_url + 'share/images/icons/' + getIcon(services[i][0]) + '.gif',
                          alt: ''
                        })
                      ]
                    })
                  ]
                }));
              }
              row_sets.push(tr);
              
              var table = _pub.createElement('table', {
                cellPadding: 0,
                cellSpacing: 0,
                styles: {
                  border: 0
                },
                children: [
                  _pub.createElement('tbody', {
                    children: row_sets
                  })
                ]
              });
              callback(table, params);
            }
          }
        }(),

        email: function() {
          var allow_message = true;
          var data_store = {};
          var msg_container = null;
          var form_container = null;

          var createInputCell = function(label, name, value) {
            return _pub.createElement('td', {
              children: [
                _pub.createElement('label', {
                  htmlFor: 'id_' + name,
                  id: 'label_' + name,
                  html: label,
                  styles: {
                    display: 'block'
                  }
                }),
                _pub.createElement('input', {
                  type: 'text',
                  name: name,
                  id: 'id_' + name,
                  value: value || ''
                })
              ]
            });
          }
          
          var validateFields = function() {
            var fields = ['frnme', 'freml', 'tonme', 'toeml'];
            var valid = true;
            for (var i=0; i<fields.length; i++) {
              var el = document.getElementById('label_shre_mail_' + fields[i]);
              if (!document.getElementById('id_shre_mail_' + fields[i]).value) {
                el.style.color = 'red';
                valid = false;
              }
              else {
                el.style.color = '';
              }
            }
            if (!valid) {
              _pub.empty(msg_container);
              msg_container.style.color = 'red';
              msg_container.appendChild(document.createTextNode('Please fill in required fields.'));
            }
            return valid;
          }

          return {
            label: 'Email',
            requires: ['link', 'title'],
            unload: function() {
              var base = document.forms['shre_form_email'];
              if (!base) return;
              data_store = _pub.serializeFormData(form_container);
            },
            render: function(callback, params) {
              
              msg_container = _pub.createElement('span', {
                styles: {
                  paddingLeft: '10px'
                }
              });
              
              row_sets = [
                _pub.createElement('tr', {
                children: [
                    createInputCell('Your name:', 'shre_mail_frnme', data_store.shre_mail_frnme),
                    createInputCell('Your email:', 'shre_mail_freml', data_store.shre_mail_freml)
                  ]
                }),
                _pub.createElement('tr', {
                  children: [
                    createInputCell("Friend's name:", 'shre_mail_tonme', data_store.shre_mail_tonme),
                    createInputCell("Friend's email:", 'shre_mail_toeml', data_store.shre_mail_toeml)
                  ]
                })
              ];
              
              if (allow_message) {
                row_sets.push(_pub.createElement('tr', {
                  children: [
                    _pub.createElement('td', {
                      colSpan: 2,
                      children: [
                        _pub.createElement('label', {
                          htmlFor: 'id_shre_mail_msg',
                          html: 'Message: ',
                          children: [
                            _pub.createElement('span', {
                              html: '(Optional)'
                            })
                          ],
                          styles: {
                            display: 'block'
                          }
                        }),
                        _pub.createElement('textarea', {
                          name: 'shre_mail_msg',
                          id: 'id_shre_mail_msg',
                          value: data_store.shre_mail_msg || ''
                        })
                      ]
                    })
                  ]
                }));
              }
              row_sets.push(_pub.createElement('tr', {
                children: [
                  _pub.createElement('td', {
                    colSpan: 2,
                    children: [
                      _pub.createElement('input', {
                        type: 'submit',
                        value: 'Send',
                        className: 'button'
                      }),
                      msg_container
                    ]
                  })
                ]
              }));
              
              form_container = _pub.createElement('form', {
                method: 'post',
                action: '.',
                name: 'shre_form_email',
                events: {
                  submit: function(e) {
                    if (!e) var e = window.event;
                    var obj = e.target ? e.target : e.srcElement;
                    if (e.preventDefault) e.preventDefault();
                    if (!validateFields()) return false;
                    _pub.empty(msg_container);
                    msg_container.appendChild(document.createTextNode('Sending Request...'));
                    var url = _pub.base_url + 'share/share.php?'+Math.floor(Math.random()*10000001);
                    data = _pub.serializeFormData(obj);
                    data.act = 'email';
                    data.link = params.link;
                    data.title = params.title;
                    _pub.ajaxRequest(url, 'GET', data, function(response) {
                      callback('<div style="padding: 20px 0; font-size: 1.2em; font-weight: bold; color: green;">' + response + '</div>', params);
                    }, function(http, response) {
                      msg_container.style.color = 'red';
                      // 400 means invalid data
                      _pub.empty(msg_container);
                      if (http.status == 400)
                        msg_container.appendChild(document.createTextNode(response));
                      else
                        msg_container.appendChild(document.createTextNode('Error processing your request.'));
                    });
                    return false;
                  }
                },
                children: [
                  table = _pub.createElement('table', {
                    cellPadding: 0,
                    cellSpacing: 0,
                    styles: {
                      border: 0
                    },
                    children: [
                      _pub.createElement('tbody', {
                        children: row_sets
                      })
                    ]
                  })
                ]
              });
              callback(form_container, params);
            }
          }
        }(),

        mypc: function() {
          function createDocumentRow(type, label, params) {
            var link = escape(params.link);
            var title = escape(params.title);
            var content = escape(params.content);
            
            return _pub.createElement('tr', {
              children: [
                _pub.createElement('td', {
                  styles: {
                    width: '10%',
                    paddingLeft: '50px'
                  },
                  children: [
                    _pub.createElement('a', {
                      href: _pub.base_url + 'share/share.php?act=mypc&f=pdf&url='+link+'&content='+content+'&title='+title,
                      title: label,
                      children: [
                        _pub.createElement('img', {
                          src: _pub.base_url + 'share/images/icons/pc_'+type+'.gif',
                          styles: {
                            width: '40px',
                            height: '40px'
                          }
                        })
                      ]
                    })
                  ]
                }),
                _pub.createElement('td', {
                  children: [
                    _pub.createElement('a', {
                      href: _pub.base_url + 'share/share.php?act=mypc&f='+type+'&url='+link+'&content='+content+'&title='+title,
                      html: label
                    })
                  ]
                })
              ]
            });
          }
          return {
            label: 'My Computer',
            requires: ['link', 'title', 'content'],
            render: function(callback, params) {
              var container = _pub.createElement('div', {
                children: [
                  _pub.createElement('table', {
                    cellPadding: 0,
                    cellSpacing: 0,
                    styles: {
                      border: 0
                    },
                    children: [
                      _pub.createElement('tbody', {
                        children: [
                          createDocumentRow('pdf', 'PDF - Portable Document Format', params),
                          createDocumentRow('word', 'Microsoft Word, Wordpad, Works', params)
                        ]
                      })
                    ]
                  })
                ]
              })

              callback(container, params);
            }
          }
        }(),

        printer: function() {
          return {
            label: 'Printer',
            requires: ['content'],
            render: function(callback, params) {
              var link = escape(params.link);
              var title = escape(params.title);
              var content = escape(params.content);
              var url = _pub.base_url+'share/share.php?mod=show&act=print&link='+link+'&title='+title+'&content='+content;

              var table = _pub.createElement('table', {
                cellPadding: 0,
                cellSpacing: 0,
                styles: {
                  border: 0
                },
                children: [
                  _pub.createElement('tbody', {
                    children: [
                      _pub.createElement('tr', {
                        children: [
                          _pub.createElement('td', {
                            styles: {
                              textAlign: 'center'
                            },
                            children: [
                              _pub.createElement('a', {
                                href: 'javascript:void(0)',
                                title: 'Print this Document',
                                events: {
                                  click: function() {
                                    window.open(_pub.base_url + 'share/share.php?act=print&link='+link+'&content='+content+'&title='+title,'','scrollbars=yes,menubar=no,height=600,width=800,resizable=yes,toolbar=no,location=no,status=no'); 
                                    return false;
                                  }
                                },
                                children: [
                                  _pub.createElement('img', {
                                    src: _pub.base_url + 'share/images/icons/print.gif',
                                    styles: {
                                      width: '40px',
                                      height: '40px'
                                    }
                                  }),
                                  _pub.createElement('div', {
                                    html: 'Print'
                                  })
                                ]
                              })
                            ]
                          })
                        ]
                      })
                    ]
                  })
                ]
              });
              callback(table, params);
            }
          }
        }()
      },
      list: new Array(),
      /**
       * Registers a plugin.
       * @param {Function} func
       * @param {Function} func
       * @param {Function} ...
       */
      register: function() {
        for (var i=0; i<arguments.length; i++) {
          _pub.plugins.list.push(arguments[i]);
          loadPlugin(arguments[i]);          
        }
        return true;
      },
      /**
       * Unregisters a plugin.
       * @param {Function} func
       * @param {Function} func
       * @param {Function} ...
       */
      unregister: function() {
        var new_list = new Array();
        var to_unregister = new Array();
        for (var i=0; i<arguments.length; i++) {
          to_unregister.push(arguments[i]);
        }
        for (var i=0; i<_pub.plugins.list.length; i++) {
          var exists = false;
          for (var z=0; z<to_unregister.length; z++) {
            if (_pub.plugins.list[i] == to_unregister[z]) exists = true;
          }
          if (!exists) new_list.push(_pub.plugins.list[i]);
        }
        if (_pub.plugins.list.length == new_list.length) return false;
        _pub.plugins.list = new_list;
        return true;
      }
    }
  };
  var containers = {
    box: null,
    menu: null,
    loading: null,
    content: null,
    content_inner: null,
    inner: null
  };
  var active_link;
  var active_tab = null;
  /**
   * Creates a new XMLHttpRequest object based on browser
   */
  var createXMLHttpRequest = function() {
    var http;
    if (window.XMLHttpRequest) { // Mozilla, Safari,...
      http = new XMLHttpRequest();
      if (http.overrideMimeType) {
        // set type accordingly to anticipated content type
        http.overrideMimeType('text/html');
      }
    }
    else if (window.ActiveXObject) { // IE
      try {
        http = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
        try {
          http = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e) {}
      }
    }
    if (!http) {
      alert('Cannot create XMLHTTP instance');
      return false;
    }
    return http;
  };
  var create = function() {
    containers.box = _pub.createElement('div', {
      id: 'share-box',
      styles: {
        display: 'none'
      },
      children: [
        _pub.createElement('a', {
          title: 'Close',
          id: 'share-close',
          href: 'javascript:void(0)',
          html: _pub.close_label,
          events: {
            click: function(e) { iBeginShare.hide(); return false; }
          }
        })
      ]
    });

    containers.inner = _pub.createElement('div', {
      id: 'share-box-inner'
    });
    
    containers.menu = _pub.createElement('ul', {
      id: 'share-menu'
    });
    containers.inner.appendChild(containers.menu);
    
    for (var i=0; i<_pub.plugins.list.length; i++) loadPlugin(_pub.plugins.list[i]);
    
    containers.content = _pub.createElement('div', {
      id: 'share-content'
    });
    containers.content.appendChild(document.createElement('br'));
    
    // TODO: update css with loading image
    containers.loading = _pub.createElement('div', {
      id: 'share-loading',
      styles: {
        display: 'none'
      }
    });
    containers.content.appendChild(containers.loading);
    
    containers.content_inner = _pub.createElement('div', {
      id: 'share-content-inner'
    });
    containers.content.appendChild(containers.content_inner);
    containers.inner.appendChild(containers.content);

    containers.box.appendChild(containers.inner);
    document.body.appendChild(containers.box);

    return containers.box;
  };
  var loadPlugin = function(plugin) {
    // if we're not initialized yet don't create it
    if (!containers.box) return;
    // <li class="class_name"><a href="#"><span>Label</span></a></li>
    var tab = _pub.createElement('li', {
      children: [
        _pub.createElement('a', {
          href: 'javascript:void(0)',
          children: [
            _pub.createElement('span', {
              html: plugin.label
            })
          ]
        })
      ]
    });
    tab.plugin = plugin;
    plugin.tab = tab;
    tab.onclick = function(e) {
      // if the current tab is active bail
      if (active_tab == tab) return false;
      _pub.showLoadingBar();
      if (active_tab.plugin.unload) active_tab.plugin.unload();
      active_tab.className = '';
      active_tab = tab;
      active_tab.className = 'active';
      plugin.render(_pub.showPlugin, active_link.params);
      return false;
    }
    containers.menu.appendChild(tab);
    return tab;
  };
  var initialize = function() {
    create();
    document.body.style.position = 'relative';
    _pub.http = createXMLHttpRequest();
    
  };
  
  _pub.addEvent(window, 'load', initialize);
  _pub.addEvent(window, 'keypress', function(e){ if (e.keyCode == (window.event ? 27 : e.DOM_VK_ESCAPE)) { iBeginShare.hide(); }});
  
  return _pub;
}();
// See readme/index.html for information on adding bookmarks
iBeginShare.plugins.builtin.bookmarks.addService('Facebook', 'http://www.facebook.com/share.php?src=bm&u=__URL__&t=__TITLE__&v=3');
iBeginShare.plugins.builtin.bookmarks.addService('Digg', 'http://digg.com/submit/?url=__URL__&title=__TITLE__');
iBeginShare.plugins.builtin.bookmarks.addService('Delicious', 'http://del.icio.us/post?&url=__URL__&title=__TITLE__');
iBeginShare.plugins.builtin.bookmarks.addService('Google', 'http://www.google.com/bookmarks/mark?op=add&title=__TITLE__&bkmk=__URL__');
iBeginShare.plugins.builtin.bookmarks.addService('Yahoo!', 'http://e.my.yahoo.com/config/edit_bookmark?.src=bookmarks&.folder=1&.name=__TITLE__&.url=__URL__&.save=+Save+');
iBeginShare.plugins.builtin.bookmarks.addService('StumbleUpon', 'http://www.stumbleupon.com/submit?url=__URL__&title=__TITLE__');
iBeginShare.plugins.builtin.bookmarks.addService('MySpace', 'http://www.myspace.com/Modules/PostTo/Pages/?t=__TITLE__&c=%20&u=__URL__&l=2');

iBeginShare.plugins.builtin.bookmarks.addService('Technorati', 'http://technorati.com/faves?add=__URL__');
iBeginShare.plugins.builtin.bookmarks.addService('Reddit', 'http://reddit.com/submit?url=__URL__&title=__TITLE__');
iBeginShare.plugins.builtin.bookmarks.addService('Ask', 'http://myjeeves.ask.com/mysearch/BookmarkIt?v=1.2&t=webpages&title=__TITLE__&url=__URL__');
iBeginShare.plugins.builtin.bookmarks.addService('Live', 'http://favorites.live.com/quickadd.aspx?url=__URL__&title=__TITLE__');
iBeginShare.plugins.builtin.bookmarks.addService('Mixx', 'http://www.mixx.com/submit?page_url=__URL__');
iBeginShare.plugins.builtin.bookmarks.addService('Blinklist', 'http://www.blinklist.com/index.php?Action=Blink/addblink.php&Url=__URL__&Title=__TITLE__');
iBeginShare.plugins.builtin.bookmarks.addService('Twitter', 'http://twitthis.com/twit?url=__URL__&title=__TITLE__');

// Uncomment any of these lines to disable plugin registration.
// Adjust the order to adjust the order of tabs.
iBeginShare.plugins.register(
  iBeginShare.plugins.builtin.bookmarks,
  iBeginShare.plugins.builtin.email,
  iBeginShare.plugins.builtin.mypc,
  iBeginShare.plugins.builtin.printer
);